import { clearToken } from '../api/config/auth.js'
import { ping } from '../helpers/apihelpers.js';
const navLinks = document.getElementsByClassName('nav-links-container')[0];
const menuToggle = document.getElementById('menuToggle');
const logoutBtn = document.getElementById('logoutBtn');

menuToggle.addEventListener('click', () => {
    navLinks.classList.toggle('active');
});

logoutBtn.addEventListener('click', () => {
    clearToken();
    window.location.href = "./signin.php";
})


document.addEventListener("visibilitychange", () => {
    if (document.visibilityState === "visible") {
        ping();
    }
});
