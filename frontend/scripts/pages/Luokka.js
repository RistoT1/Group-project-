import { loadLuokka, GetVarauksetByLuokka, lisaaVaraus } from "../helpers/apihelpers.js";

class Luokka {
    constructor(startTime = "08:30:00", endTime = "17:30:00", incremet = "01:00:00") {
        this.startTime = startTime;
        this.endTime = endTime;
        this.increment = incremet;

        this.luokkaData = null; //Luokan tiedot
        this.takenTimes = []; //varatut ajat
        this.LuokkaID = null;
        this.currentWeekStart = this.getStartOfWeek(new Date()); // viiikkojen navigointiin
        this.numberOfDays = this.getNumberOfDays(); // jos mobiilissa renderöi rivit 3 + 2 

        // Update number of days on resize
        window.addEventListener('resize', () => {
            this.numberOfDays = this.getNumberOfDays();
            this.renderPage();
            this.addEventListeners();
        });

        this.init();
    }

    async init() {
        const urlParams = new URLSearchParams(window.location.search);
        this.LuokkaID = urlParams.get('id');

        this.luokkaData = await loadLuokka(this.LuokkaID);
        this.takenTimes = await GetVarauksetByLuokka(this.LuokkaID);

        this.renderPage();
        this.addEventListeners();
    }

    getNumberOfDays() {
        return window.innerWidth < 768 ? 3 : 5; //puhelimella 3 päivää rivissä
    }

    getStartOfWeek(date) {
        const d = new Date(date);
        const day = d.getDay(); // sunnuntai = 0
        const diff = d.getDate() - day + (day === 0 ? -6 : 1); // Laskee ekan päivän maanantaiksi
        //keskiviikko = 3 
        // päivämäärä 12
        // päivämäärä - 3 = 9 = sunnuntai + 1
        //maanantai  10
        //jos päivä olisi sunnuntai(0): -6 jotta saadaan kyseisen viikon maanantai
        return new Date(d.setDate(diff));
    }

    getWeekDates() {
        // Always generate 5 consecutive weekdays (Monday–Friday)
        return Array.from({ length: 5 }, (_, i) => {
            const date = new Date(this.currentWeekStart);
            date.setDate(date.getDate() + i);
            return date;
        });
    }


    getTimeSlots() {
        const slots = [];
        let [hours, minutes] = this.startTime.split(':').map(Number); //aloitus tunnit ja minuutit 
        const [endHours, endMinutes] = this.endTime.split(':').map(Number);// lopetus tunnit ja minuutit 
        let [incrementHours, incrementMinutes] = this.increment.split(':').map(Number);

        //jos tunnit yli tai minuutit yli 
        while (hours < endHours || (hours === endHours && minutes < endMinutes)) {
            //tarkistaa onhan tunnit ja minuutit kaksi numeroa ja korjaa ne jos ei ole
            // 00 ja 00
            const hh = String(hours).padStart(2, '0');
            const mm = String(minutes).padStart(2, '0');
            slots.push(`${hh}:${mm}`);
            hours += incrementHours;
            minutes += incrementMinutes;
            if (minutes >= 60) {
                hours += 1;
                minutes = 0;
            }
        }

        return slots;
    }

    getEquipmentIcon(equipmentName) {
        const icons = {
            "projektori": "fa-solid fa-video",
            "valkotaulu": "fa-solid fa-chalkboard",
            "tietokoneet": "fa-solid fa-computer",
            "äänentoisto": "fa-solid fa-volume-up",
            "3d-tulostin": "fa-solid fa-print",
            "laboratoriovarusteet": "fa-solid fa-flask",
            "kemikaalit": "fa-solid fa-vial",
            "mummon telamiinat": "fa-solid fa-cookie",
        };
        return icons[equipmentName.toLowerCase()] || "fa-solid fa-circle"; // default icon
    }

    renderPage() {
        const container = document.getElementById('luokkaContainner');
        if (!container) return;

        const weekDates = this.getWeekDates();
        const timeSlots = this.getTimeSlots();
        const luokka = this.luokkaData[0];

        container.innerHTML = `
        <h2>${luokka.Nimi}</h2>
        <div class="luokka-grid">
            <div class="div1">
                <div class="img-container">
                    <img src="../assets/123.jpg" alt="${luokka.Nimi}">
                </div>
            </div>
            <div class="div2">
                <div class="info-container">
                    <h3>Tiedot ja varusteet</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorem aperiam, impedit placeat
                            maiores ipsam dolores aspernatur tenetur officiis esse beatae non ab nesciunt numquam?
                            Provident, fugit amet. Repellendus, dignissimos corrupti?</p>
                    <p><i class="fa-solid fa-location-dot"></i> Sijainti: ${luokka.Sijainti}</p>
                    <p><i class="fa-solid fa-people-group"></i> Kapasiteetti: ${luokka.Kapasiteetti}</p>
                    <div class="equipments">
                        ${luokka.Varusteet.split(',').map(eq => `
                            <p>
                                <i class="${this.getEquipmentIcon(eq.trim())}"></i>
                                ${eq.trim()}
                            </p>`).join('')}
                    </div>
                </div>
            </div>
            <div class="div3">
                <div class="calendar-container">
                    <div class="header-row">
                        <h3>Varattavat ajat</h3>
                        <p>
                            <span id="prevWeek">&lt;</span> 
                            ${weekDates[0].toLocaleDateString('fi-FI')} - ${weekDates[weekDates.length - 1].toLocaleDateString('fi-FI')}
                            <span id="nextWeek">&gt;</span>
                        </p>
                    </div>
                    <div class="scrollable-grid">
                        ${this.renderCalendarGrid(weekDates, timeSlots)}
                    </div>
                </div>
            </div>
            <div class="div4">
                <div class="booking-container">
                    <h3>Tee Varaus</h3>
                    <div class="booking">
                        <div class="booking-info">
                            <div class="info-item">
                                <p class="label">Päivämäärä</p>
                                <p class="value" id="bookingDate">Valitse aika</p>
                            </div>
                            <div class="info-item">
                                <p class="label">Aika</p>
                                <p class="value" id="bookingTime">Valitse aika</p>
                            </div>
                        </div>
                        <div class="reason-container">
                            <p class="label">Varauksen Tarkoitus</p>
                            <input id="reasonInput" class="reason-input" type="text" placeholder="Esim. Matematiikan tunti, ryhmä 9A">
                        </div>
                        <div class="book-button-container">
                            <button class="book-button" id="bookButton">Varaa Luokkahuone</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        `;
    }

    renderCalendarGrid(weekDates, timeSlots) {
        const isMobile = this.getNumberOfDays() === 3; // mobile: 3+2 split

        let html = '';

        if (isMobile) {
            // Split into 3 days and 2 days
            const firstGroup = weekDates.slice(0, 3); // ma, ti, ke
            const secondGroup = weekDates.slice(3);   // to, pe

            html += this.renderCalendarGroup(firstGroup, timeSlots, isMobile);
            html += this.renderCalendarGroup(secondGroup, timeSlots, isMobile);
        } else {
            // Desktop: show all 5 days in one row
            html += this.renderCalendarGroup(weekDates, timeSlots);
        }

        return html;
    }

    renderCalendarGroup(datesGroup, timeSlots, isMobile) {
        // Day names in short or long format
        const dayNames = isMobile
            ? datesGroup.map(date =>
                date.toLocaleDateString('fi-FI', { weekday: 'short' })
            )
            : datesGroup.map(date =>
                date.toLocaleDateString('fi-FI', { weekday: 'long' })
            );

        // Header row
        let html = `
        <div class="dates-header">
            <div class="date-column empty"></div>
            ${dayNames.map((day, i) => `
                <div class="date-column">
                    <div class="day-name">${day}</div>
                    <div class="date-number">${datesGroup[i].getDate()}</div>
                </div>
            `).join('')}
        </div>
        `;

        // Time slots
        html += timeSlots.map(time => {
            let row = `<div class="grid-row"><div class="time-label">${time}</div>`;
            datesGroup.forEach(date => {
                const dateStr = date.toISOString().split('T')[0];

                // lisätään sekunnit vertauksen toimivuuden vuoksi
                const timeWithSeconds = `${time}:00`;

                const booked = this.takenTimes.find(
                    t => t.Paivamaara === dateStr
                        && t.AloitusAika <= timeWithSeconds
                        && t.LopetusAika > timeWithSeconds
                        && t.Tila !== "peruttu"
                );
                row += `<div class="slot ${booked ? 'disabled' : 'vapaa'}" data-date="${dateStr}">${booked ? 'varattu' : 'Vapaa'}</div>`;
            });
            row += '</div>';
            return row;
        }).join('');

        return html;
    }



    addEventListeners() {
        // Slot selection
        document.querySelectorAll('.slot.vapaa').forEach(slot => {
            slot.addEventListener('click', e => {
                // Remove previous selection
                document.querySelectorAll('.slot.valittu').forEach(s => s.classList.remove('valittu'));
                e.currentTarget.classList.add('valittu');

                // Get start time from clicked slot (e.g. "09:00")
                const timeLabel = e.currentTarget.parentElement.querySelector('.time-label').textContent;
                const [hours, minutes] = timeLabel.split(':').map(Number);

                // Parse increment string (e.g. "01.00.00" or "00.30.00")
                const parts = this.increment.split(':').map(Number);
                const incHours = parts[0] || 0;
                const incMinutes = parts[1] || 0;
                const incSeconds = parts[2] || 0;

                // Compute total seconds
                let totalSeconds =
                    (hours * 3600) +
                    (minutes * 60) +
                    (incHours * 3600) +
                    (incMinutes * 60) +
                    incSeconds;

                // Handle wrap around midnight
                const wrapsNextDay = totalSeconds >= 24 * 3600;
                if (wrapsNextDay) totalSeconds -= 24 * 3600;

                const endHours = Math.floor(totalSeconds / 3600);
                const endMinutes = Math.floor((totalSeconds % 3600) / 60);
                const endTime = `${String(endHours).padStart(2, '0')}:${String(endMinutes).padStart(2, '0')}`;

                const fullDate = e.currentTarget.dataset.date;
                let bookingDate = new Date(fullDate);

                // If we wrapped past midnight, move booking end date to next day
                if (wrapsNextDay) {
                    bookingDate.setDate(bookingDate.getDate() + 1);
                }

                // Update booking info display
                const dayName = bookingDate.toLocaleDateString('fi-FI', { weekday: 'long' });
                document.getElementById('bookingDate').textContent = dayName;
                document.getElementById('bookingDate').dataset.fullDate = bookingDate.toISOString().split('T')[0];
                document.getElementById('bookingTime').textContent = `${timeLabel}–${endTime}`;

            });
        });


        // Week navigation
        document.getElementById('prevWeek').addEventListener('click', () => {
            this.currentWeekStart.setDate(this.currentWeekStart.getDate() - 7);
            this.renderPage();
            this.addEventListeners();
        });

        document.getElementById('nextWeek').addEventListener('click', () => {
            this.currentWeekStart.setDate(this.currentWeekStart.getDate() + 7);
            this.renderPage();
            this.addEventListeners();
        });

        // Booking button
        document.getElementById('bookButton').addEventListener('click', async () => {
            // Get input values
            const reason = document.getElementById('reasonInput').value.trim();
            const selectedDate = document.getElementById('bookingDate').dataset.fullDate;
            const selectedTime = document.getElementById('bookingTime').textContent;

            // Validation
            if (!reason || selectedTime === 'Valitse aika') {
                alert('Täytä kaikki tiedot ennen varausta!');
                return;
            }

            // Split time into start and end
            const [startTime, endTime] = selectedTime.split('–');

            // Helper to convert HH:MM -> HH:MM:SS for SQL
            function formatTime(timeStr) {
                const [h, m] = timeStr.split(':').map(Number);
                const hh = String(h).padStart(2, '0');
                const mm = String(m).padStart(2, '0');
                const ss = '00'; // default seconds
                return `${hh}:${mm}:${ss}`;
            }

            const startTimeSql = formatTime(startTime);
            const endTimeSql = formatTime(endTime);

            console.log(endTimeSql);

            console.log(this.LuokkaID);
            const data = await lisaaVaraus(this.LuokkaID, selectedDate, startTimeSql, endTimeSql, reason);
            console.log(data);

            // Update the specific slot instead of re-rendering
            const selectedSlot = document.querySelector('.slot.valittu');
            if (selectedSlot) {
                selectedSlot.classList.remove('vapaa', 'valittu');
                selectedSlot.classList.add('disabled');
                selectedSlot.textContent = 'varattu';
                // Remove click listener
                selectedSlot.replaceWith(selectedSlot.cloneNode(true));
            }

            // Add new booking to takenTimes array
            this.takenTimes.push({
                Paivamaara: selectedDate,
                AloitusAika: startTime,
                LopetusAika: endTime,
                Tarkoitus: reason
            });

            // Reset booking form
            document.getElementById('bookingDate').textContent = 'Valitse aika';
            document.getElementById('bookingDate').dataset.fullDate = '';
            document.getElementById('bookingTime').textContent = 'Valitse aika';
            document.getElementById('reasonInput').value = '';

            alert('Varaus onnistui!');
        });

    }
}

// Initialize with custom times
document.addEventListener('DOMContentLoaded', () => {
    new Luokka("08:30:00", "17:30:00");
});