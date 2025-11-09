import { loginRequest } from "../api/auth/loginRequest.js";
import { setToken } from "../api/config/auth.js";
class SigninPage {
    constructor() {
        this.form = document.getElementById('sigin-form');
        this.initEventListeners();
    }
    initEventListeners() {
        this.form.addEventListener('submit', this.handleSubmit.bind(this));
    }
    async handleSubmit(e) {
        e.preventDefault();
        const formData = new FormData(this.form);
        const payload = {
            method: "POST",
            body: JSON.stringify({
                kirjaudu: true,
                sahkoposti: formData.get('sahkoposti'),
                salasana: formData.get('salasana'),
            }),
        };
        try {
            const response = await loginRequest(payload)
            if (response.success) {
                setToken(response.token);
                console.log("Kirjautuminen onnistui", response);
                response.data.rooli === 'ylläpitäjä' ?
                    window.location.href = "./adminDashboard.php" :
                    window.location.href = "./ClassList.php";
            } else {
                alert("Kirjautuminen epäonnistui: " + response.message);
            }
        } catch (error) {
            alert("Virhe kirjautumisessa: " + error.message);
        }
    }
}
document.addEventListener('DOMContentLoaded', () => {
    new SigninPage();
});
