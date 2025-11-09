import { apiRequest } from "../api/apiRequest.js";

class classListPage {
    constructor() {
        this.loadClasses();
    }
    async loadClasses() {
        const payload = {
            Headers: {
                "Content-Type": "application/json",
            },
        };
        try {
            const response = await apiRequest("?luokat=true", payload);
            const fragment = document.createDocumentFragment();
            for (const Luokka of response.data) {
                const div = document.createElement("div");
                div.className = "class-item";
                div.innerHTML = `
                    <h3>${Luokka.Nimi}</h3>
                    <p>${Luokka.Varusteet}</p>
                    <p>${Luokka.Kapasiteetti}</p>
                    <p>${Luokka.Sijainti}</p>
                    <p class="luokka-tila">${(Luokka.Tila || "").trim() || "Vapaa"}<p>
                `;
                fragment.appendChild(div);
            }
            document.getElementById("classList").appendChild(fragment);
        } catch (error) {
            console.error("Virhe luokkien lataamisessa:", error);
        }
    }
}
document.addEventListener('DOMContentLoaded', () => {
    new classListPage();
});