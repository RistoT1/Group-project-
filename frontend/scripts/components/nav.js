const navLinks = document.getElementsByClassName('nav-links-container')[0];
const menuToggle = document.getElementById('menuToggle');

menuToggle.addEventListener('click', () => {
    navLinks.classList.toggle('active');
});