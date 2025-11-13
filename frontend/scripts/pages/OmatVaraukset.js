import { getKayttajaVaraukset } from "../helpers/apihelpers.js";

class Varaus {
    constructor(data) {
        this.luokkaID = data.LuokkaID;
        this.tila = data.Tila || "tuntematon";
        this.paivamaara = data.Paivamaara;
        this.aloitusAika = data.AloitusAika;
        this.lopetusAika = data.LopetusAika;
        this.tarkoitus = data.Tarkoitus;
        this.varausID = data.VarausID;
        this.kayttajaID = data.KayttajaID;
    }

    isValid() {
        // Ignore bookings with invalid placeholder dates
        return this.luokkaID != null && this.paivamaara && this.paivamaara !== "0000-00-00" && this.aloitusAika && this.aloitusAika !== "00:00:00";
    }

    isUpcoming() {
        const now = new Date();
        const bookingDate = new Date(`${this.paivamaara}T${this.aloitusAika}`);
        return bookingDate > now;
    }

    toHTML() {
        return `
            <div class="booking-card">
                <p>Luokka: ${this.luokkaID}</p>
                <p>Päivämäärä: ${this.paivamaara}</p>
                <p>Aloitusaika: ${this.aloitusAika}</p>
                <p>Lopetusaika: ${this.lopetusAika}</p>
                <p>Tarkoitus: ${this.tarkoitus || "-"}</p>
                <p>Tila: ${this.tila}</p>
            </div>
        `;
    }
}

// Booking Manager
class BookingManager {
    constructor() {
        this.allBookings = [];
        this.filteredBookings = [];
        this.elements = {
            loading: document.getElementById('loadingContainer'),
            stats: document.getElementById('statsContainer'),
            filters: document.getElementById('filtersContainer'),
            bookings: document.getElementById('bookingsContainer'),
            roomFilter: document.getElementById('roomFilter'),
            statusFilter: document.getElementById('statusFilter'),
            sortFilter: document.getElementById('sortFilter')
        };
    }

    async init() {
        try {
            await this.loadBookings();
            this.setupEventListeners();
        } catch (error) {
            this.handleError(error);
        }
    }

    async loadBookings() {
        try {
            if (this.elements.loading) this.elements.loading.style.display = 'block';

            const data = await getKayttajaVaraukset();
            if (!Array.isArray(data)) throw new Error("API did not return an array");

            this.allBookings = data
                .map(item => new Varaus(item))
                .filter(varaus => varaus.isValid());

            this.filteredBookings = [...this.allBookings];

            if (this.elements.loading) this.elements.loading.style.display = 'none';

            if (this.allBookings.length === 0) {
                this.showEmptyState();
            } else {
                this.updateStats();
                this.populateFilters();
                this.render();
                if (this.elements.stats) this.elements.stats.style.display = 'grid';
                if (this.elements.filters) this.elements.filters.style.display = 'block';
            }
        } catch (error) {
            this.handleError(error);
        }
    }

    setupEventListeners() {
        if (this.elements.roomFilter)
            this.elements.roomFilter.addEventListener('change', () => this.applyFilters());
        if (this.elements.statusFilter)
            this.elements.statusFilter.addEventListener('change', () => this.applyFilters());
        if (this.elements.sortFilter)
            this.elements.sortFilter.addEventListener('change', () => this.applyFilters());
    }

    applyFilters() {
        const roomFilter = this.elements.roomFilter?.value || 'all';
        const statusFilter = this.elements.statusFilter?.value || 'all';
        const sortFilter = this.elements.sortFilter?.value || '';

        this.filteredBookings = this.allBookings.filter(booking => {
            const roomMatch = roomFilter === 'all' || booking.luokkaID.toString() === roomFilter;
            const statusMatch = statusFilter === 'all' || booking.tila.toLowerCase() === statusFilter.toLowerCase();
            return roomMatch && statusMatch;
        });

        this.sortBookings(sortFilter);
        this.render();
        this.updateStats();
    }

    sortBookings(sortType) {
        this.filteredBookings.sort((a, b) => {
            const dateA = new Date(`${a.paivamaara}T${a.aloitusAika}`);
            const dateB = new Date(`${b.paivamaara}T${b.aloitusAika}`);
            switch (sortType) {
                case 'date-asc': return dateA - dateB;
                case 'date-desc': return dateB - dateA;
                case 'time-asc': return a.aloitusAika.localeCompare(b.aloitusAika);
                case 'time-desc': return b.aloitusAika.localeCompare(a.aloitusAika);
                default: return 0;
            }
        });
    }

    updateStats() {
        if (!this.elements.stats) return;
        const upcomingCount = this.filteredBookings.filter(b => b.isUpcoming()).length;
        const uniqueRooms = new Set(this.filteredBookings.map(b => b.luokkaID)).size;

        const totalEl = document.getElementById('totalBookings');
        const upcomingEl = document.getElementById('upcomingBookings');
        const roomsEl = document.getElementById('uniqueRooms');

        if (totalEl) totalEl.textContent = this.filteredBookings.length;
        if (upcomingEl) upcomingEl.textContent = upcomingCount;
        if (roomsEl) roomsEl.textContent = uniqueRooms;
    }

    populateFilters() {
        if (!this.elements.roomFilter || !this.elements.statusFilter) return;

        // Room filter
        this.elements.roomFilter.innerHTML = `<option value="all">Kaikki luokat</option>`;
        const uniqueRooms = [...new Set(this.allBookings.map(b => b.luokkaID))].sort((a, b) => a - b);
        uniqueRooms.forEach(roomId => {
            const option = document.createElement('option');
            option.value = roomId;
            option.textContent = `Luokka ${roomId}`;
            this.elements.roomFilter.appendChild(option);
        });

        // Status filter
        this.elements.statusFilter.innerHTML = `<option value="all">Kaikki tilat</option>`;
        const uniqueStatuses = [...new Set(this.allBookings.map(b => b.tila.toLowerCase()))];
        uniqueStatuses.forEach(status => {
            const option = document.createElement('option');
            option.value = status;
            option.textContent = status.charAt(0).toUpperCase() + status.slice(1);
            this.elements.statusFilter.appendChild(option);
        });
    }

    render() {
        if (!this.elements.bookings) return;

        if (this.filteredBookings.length === 0) {
            this.elements.bookings.innerHTML = `
                <div class="empty-state">
                    <h2>Ei varauksia</h2>
                    <p>Valituilla suodattimilla ei löytynyt varauksia</p>
                </div>
            `;
            return;
        }

        this.elements.bookings.innerHTML = this.filteredBookings
            .map(booking => booking.toHTML())
            .join('');
    }

    showEmptyState() {
        if (!this.elements.bookings) return;
        this.elements.bookings.innerHTML = `
            <div class="empty-state">
                <h2>Ei varauksia vielä</h2>
                <p>Sinulla ei ole vielä yhtään varausta</p>
            </div>
        `;
    }

    handleError(error) {
        console.error('Error:', error);
        if (this.elements.loading) {
            this.elements.loading.innerHTML =
                '<div style="color: #dc3545;">Virhe ladattaessa varauksia</div>';
        }
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    const bookingManager = new BookingManager();
    bookingManager.init();
});
