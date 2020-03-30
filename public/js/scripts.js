if (document.getElementById('user-profile-image')) {
    document.getElementById('user-profile-image').addEventListener('click', toggleProfileMenu);
    document.getElementById('profile-menu-background').addEventListener('click', toggleProfileMenu);
}

function toggleProfileMenu() {
    document.body.classList.toggle('show-profile-menu');
}
