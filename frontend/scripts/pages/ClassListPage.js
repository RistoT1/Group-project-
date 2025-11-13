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
        const startTime = this.DOM.startTimeInput.value.trim();
        const endTime = this.DOM.endTimeInput.value.trim();
        const date = this.DOM.dateInput.value.trim() || new Date().toISOString().split('T')[0];

        console.log("Filters:", { building, capacity, startTime, endTime, date });

        // Helper function to normalize time format (add :00 seconds if missing)
        const normalizeTime = (time) => {
            if (!time) return '';
            if (time.split(':').length === 3) return time;
            return time + ':00';
        };

        const normalizedStartTime = normalizeTime(startTime);
        const normalizedEndTime = normalizeTime(endTime);

        // If capacity is 0 or NaN, ignore it
        if (isNaN(capacity) || capacity === 0) capacity = null;

        // Filter classes
        const filtered = this.Luokat.filter(luokka => {

            if (building && building !== "all" && luokka.Sijainti.charAt(0) !== building) return false;

            // Filter by capacity (only if capacity is specified)
            if (capacity && luokka.Kapasiteetti < capacity) return false;

            // Filter by availability if start and end times are provided
            if (normalizedStartTime && normalizedEndTime) {
                const conflicting = this.takenTimes.some(reservation => {
                    return reservation.LuokkaID === luokka.LuokkaID &&
                        reservation.Paivamaara === date &&
                        !(
                            normalizedEndTime <= reservation.AloitusAika ||
                            normalizedStartTime >= reservation.LopetusAika
                        );
                });
                if (conflicting) return false;
            }

            return true; // Passed all filters
        });
        console.log("filtered", filtered);
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