import { signin } from "../api/auth/signin.js";
class SigninPage {
    constructor() {
        this.form = document.getElementById('sigin-form');
        this.initEventListeners();
    }
    initEventListeners() {
        this.form.addEventListener('submit', this.handleSubmit.bind(this));
    }
    handleSubmit(event) {
        event.preventDefault();
        const formData = new FormData(this.form);
        const userData = {
            username: formData.get('user'),
            password: formData.get('pwd')
        };
        signin(userData.username, userData.password);
    }
}
document.addEventListener('DOMContentLoaded', () => {
    new SigninPage();
});
