import { loadLuokka, loadTakenTimes } from "../helpers/apihelpers.js";

class Luokka{
    constructor(){
        this.Luokka = [];
        this.TimeValidation = [];
        this.TakenTimes = [];
        this.init();
    }

    async init(){
        this.TakenTimes = await loadTakenTimes();
        const urlParams = new URLSearchParams(window.location.search);
        const luokkaID = urlParams.get('id');
        this.Luokka = await loadLuokka(luokkaID);
        console.log("luokat", this.Luokka);
    }
}
document.addEventListener('DOMContentLoaded', () => {
    new Luokka();
});
