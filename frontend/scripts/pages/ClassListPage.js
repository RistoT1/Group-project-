import { apiRequest } from "../api/apiRequest.js";

class classListPage {
    constructor() {
        this.times = [];
        this.originalData = []; // Keep original data intact
        this.DOM = {
            classList: document.getElementById("classList"),
            filterBtn: document.getElementById("applyFiltersBtn"),
            buildingSelect: document.getElementById("buildingSelect"),
            capacitySelect: document.getElementById("capacitySelect"),
            startTimeInput: document.getElementById("startTimeInput"),
            endTimeInput: document.getElementById("endTimeInput"),
            dateInput: document.getElementById("dateInput"),
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
            // Load both data sources in parallel and wait for both
            await Promise.all([
                this.loadClasses(),
                this.loadTimes()
            ]);
            console.log("Kaikki data ladattu");
        } catch (error) {
            console.error("Virhe datan lataamisessa:", error);
            this.showError("Tietojen lataus epäonnistui");
        }
    }

    addEventListeners() {
        this.DOM.filterBtn.addEventListener("click", () => this.applyFilters());
    }

    applyFilters() {
        // Get raw filter values
        const building = this.DOM.buildingSelect.value.trim();
        const capacity = this.DOM.capacitySelect.value.trim();
        const startTime = this.DOM.startTimeInput.value.trim();
        const endTime = this.DOM.endTimeInput.value.trim();
        const date = this.DOM.dateInput.value.trim() || new Date().toISOString().split('T')[0];

        console.log("Filters:", { building, capacity, startTime, endTime, date });

        // Helper function to normalize time format (add :00 seconds if missing)
        const normalizeTime = (time) => {
            if (!time) return '';
            // If time is already in HH:MM:SS format, return as is
            if (time.split(':').length === 3) return time;
            // If time is in HH:MM format, add :00
            return time + ':00';
        };

        // Normalize user input times
        const normalizedStartTime = normalizeTime(startTime);
        const normalizedEndTime = normalizeTime(endTime);

        // First, filter times by date to get only relevant time slots
        const timesForDate = this.times.filter(time => time.Paivamaara === date);

        console.log(`Time slots for ${date}:`, timesForDate);

        // Then filter rooms based on all criteria
        const filteredData = this.originalData.filter((luokka) => {
            // Building filter
            if (building && building !== "" && building !== "all" && building !== "Kaikki") {
                if (luokka.Sijainti !== building) {
                    return false;
                }
            }

            // Capacity filter
            if (capacity && capacity !== "" && capacity !== "0") {
                if (parseInt(luokka.Kapasiteetti) < parseInt(capacity)) {
                    return false;
                }
            }

            // Date filter - ALWAYS check if room has time slots on selected date
            const aikaSlots = timesForDate.filter(time => time.LuokkaID === luokka.LuokkaID);

            console.log(`Luokka ${luokka.LuokkaID} aikaslotit:`, aikaSlots);

            // If no time slots for this room on this date, exclude it
            if (!aikaSlots || aikaSlots.length === 0) {
                return false;
            }

            // Additional time filter - only if user specified start or end time
            if ((startTime && startTime !== "") || (endTime && endTime !== "")) {

                // If only start time is specified (no end time)
                if (normalizedStartTime && (!normalizedEndTime || normalizedEndTime === "")) {
                    const hasAvailableSlot = aikaSlots.some(slot => {
                        // Show slots that START at or after the requested start time
                        const startsAfter = slot.AloitusAika >= normalizedStartTime;
                        console.log(`Checking slot starts ${slot.AloitusAika} >= ${normalizedStartTime}: ${startsAfter}`);
                        return startsAfter;
                    });

                    if (!hasAvailableSlot) {
                        return false;
                    }
                }
                // If only end time is specified (no start time)
                else if (normalizedEndTime && (!normalizedStartTime || normalizedStartTime === "")) {
                    const hasAvailableSlot = aikaSlots.some(slot => {
                        // Show slots that END at or before the requested end time
                        const endsBefore = slot.LopetusAika <= normalizedEndTime;
                        console.log(`Checking slot ends ${slot.LopetusAika} <= ${normalizedEndTime}: ${endsBefore}`);
                        return endsBefore;
                    });

                    if (!hasAvailableSlot) {
                        return false;
                    }
                }
                // If both start and end time are specified
                else {
                    const hasOverlap = aikaSlots.some(slot => {
                        const slotStart = slot.AloitusAika;
                        const slotEnd = slot.LopetusAika;

                        // True overlap means the time periods actually intersect, not just touch
                        // Overlap exists if: slot starts BEFORE user ends AND slot ends AFTER user starts
                        const overlaps = slotStart < normalizedEndTime && slotEnd > normalizedStartTime;

                        console.log(`Checking slot ${slotStart}-${slotEnd} vs ${normalizedStartTime}-${normalizedEndTime}: ${overlaps}`);

                        return overlaps;
                    });

                    if (!hasOverlap) {
                        return false;
                    }
                }
            }

            return true;
        });

        console.log("Suodatetut luokat:", filteredData);
        this.renderClasses(filteredData);
    }
    async loadClasses() {
        const payload = {
            Headers: {
                "Content-Type": "application/json",
            },
        };
        try {
            const response = await apiRequest("?luokat=true", payload);
            this.originalData = response.data; // Store original data
            this.renderClasses(response.data);
            console.log("Ladatut luokat:", this.originalData);
        } catch (error) {
            console.error("Virhe luokkien lataamisessa:", error);
            throw error;
        }
    }

    async loadTimes() {
        const payload = {
            Headers: {
                "Content-Type": "application/json",
            },
        };
        try {
            const response = await apiRequest("?ajat=true", payload);
            console.log("Aikavaste:", response);
            this.times = response.data;
            console.log("Ladatut ajat:", this.times);
        } catch (error) {
            console.error("Virhe aikojen lataamisessa:", error);
            throw error;
        }
    }

    renderClasses(classes) {
        // Clear existing content before rendering
        this.DOM.classList.innerHTML = '';

        const fragment = document.createDocumentFragment();
        for (const Luokka of classes) {
            const div = document.createElement("div");
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