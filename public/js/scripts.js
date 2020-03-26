document.getElementById('profile').addEventListener('click', toggleProfileMenu);
document.getElementById('profile-menu-background').addEventListener('click', toggleProfileMenu);

function toggleProfileMenu() {
    document.body.classList.toggle('show-profile-menu');
}