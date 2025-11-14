import { loadClasses, loadTakenTimes } from "../helpers/apihelpers.js";
class classListPage {
    constructor() {
        this.takenTimes = [];
        this.Luokat = [];
        this.DOM = {
            classList: document.getElementById("classList"),
            filterBtn: document.getElementById("applyFiltersBtn"),
            buildingSelect: document.getElementById("buildingSelect"),
            capacitySelect: document.getElementById("capacitySelect"),
            startTimeInput: document.getElementById("startTimeInput"),
            endTimeInput: document.getElementById("endTimeInput"),
            dateInput: document.getElementById("dateInput"),
            searchBar: document.getElementById("haku"),
            resetBtn: document.getElementById("resetFilters")
        };

        for (const key in this.DOM) {
            if (this.DOM[key] === null) {
                console.warn(`Elementti ${key} ei löytynyt DOM:sta.`);
            }
        }
        dateInput.valueAsDate = new Date();
        this.addEventListeners();
        this.initialize();
    }

    async initialize() {
        try {
            const [classes, takenTimes] = await Promise.all([
                loadClasses(),
                loadTakenTimes()
            ]);

            this.Luokat = classes;
            this.renderClasses(classes);
            this.takenTimes = takenTimes;
            this.applyFilters();
        } catch (error) {
            console.error("Virhe datan lataamisessa:", error);
            this.showError("Tietojen lataus epäonnistui");
        }
    }

    addEventListeners() {
        this.DOM.filterBtn.addEventListener("click", () => this.applyFilters());
        this.DOM.searchBar.addEventListener("input", (e) => this.filterByName(e.target.value));
        this.DOM.resetBtn.addEventListener("click", () => this.resetFilters());
    }

    filterByName(haku) {
        const searchTerm = haku.toLowerCase();
        this.haku = this.Luokat.filter(luokka =>
            luokka.Nimi.toLowerCase().split(" ").slice(1).join(" ").startsWith(searchTerm)
        );
        this.renderClasses(this.haku);
    }
    resetFilters() {
        this.DOM.buildingSelect.value = 'all';
        this.DOM.capacitySelect.value = '0';
        this.DOM.startTimeInput.value = '';
        this.DOM.endTimeInput.value = '';
        this.DOM.dateInput.value = new Date().toISOString().split('T')[0];
        this.DOM.searchBar.value = '';
        this.haku = [];
        this.renderClasses(this.Luokat);
    }

    applyFilters() {
        const building = this.DOM.buildingSelect.value.trim();
        let capacity = parseInt(this.DOM.capacitySelect.value.trim(), 10);
        const now = new Date();
        const pad = (n) => n.toString().padStart(2, '0');

        const currentTime = `${pad(now.getHours())}:${pad(now.getMinutes())}`;
        const oneHourLater = new Date(now.getTime() + 60 * 60 * 1000);
        const endTimeDefault = `${pad(oneHourLater.getHours())}:${pad(oneHourLater.getMinutes())}`;

        const startTime = this.DOM.startTimeInput.value.trim() || currentTime;
        const endTime = this.DOM.endTimeInput.value.trim() || endTimeDefault;
        const date = this.DOM.dateInput.value.trim() || new Date().toISOString().split('T')[0];

        const normalizeTime = (time) => {
            if (!time) return '';
            if (time.split(':').length === 3) return time;
            return time + ':00';
        };

        const normalizedStartTime = normalizeTime(startTime);
        const normalizedEndTime = normalizeTime(endTime);

        if (isNaN(capacity) || capacity === 0) capacity = null;

        const filtered = this.Luokat.filter(luokka => {

            // building filter
            if (building && building !== "all" && luokka.Sijainti.charAt(0) !== building)
                return false;

            // capacity filter
            if (capacity && luokka.Kapasiteetti < capacity)
                return false;

            // time conflict filter
            const conflicting = this.takenTimes.some(reservation => {
                const tila = (reservation.Tila || "").trim().toLowerCase();

                // Ignore cancelled
                if (tila === "peruttu") return false;

                // Ignore invalid dates
                if (!reservation.Paivamaara || reservation.Paivamaara === "0000-00-00") return false;

                // Ignore zero-length or broken reservations
                if (reservation.AloitusAika >= reservation.LopetusAika) return false;

                // Check same class and date
                if (reservation.LuokkaID !== luokka.LuokkaID) return false;
                if (reservation.Paivamaara !== date) return false;

                // Check overlapping
                return !(
                    normalizedEndTime <= reservation.AloitusAika ||
                    normalizedStartTime >= reservation.LopetusAika
                );
            });



            if (conflicting) return false;

            return true;
        });

        this.renderClasses(filtered);
    }



    renderClasses(classes) {
        // Clear existing content before rendering
        this.DOM.classList.innerHTML = '';

        const fragment = document.createDocumentFragment();
        for (const Luokka of classes) {
            const div = document.createElement("div");
            div.addEventListener('click', (e) => {
                window.location.href = `./luokka.php?id=${Luokka.LuokkaID}`;
            });

            div.id = Luokka.LuokkaID;
            div.className = "class-item";
            div.innerHTML = `
                <h3>${Luokka.Nimi}</h3>
                <p>${Luokka.Varusteet}</p>
                <p>${Luokka.Kapasiteetti}</p>
                <p>${Luokka.Sijainti}</p>
                <p class="luokka-tila">${(Luokka.Tila || "").trim() || "Vapaa"}</p>
            `;
            fragment.appendChild(div);
        }
        this.DOM.classList.appendChild(fragment);

    }

    showError(message) {
        this.DOM.classList.innerHTML = `<p class="error">${message}</p>`;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new classListPage();
});