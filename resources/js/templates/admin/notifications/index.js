(function () {
    var notificationMainContainer = document.getElementById('notification-main-container');
    var markAsReadButtons = document.querySelectorAll('.mark-as-read');
    var notificationMenu = document.querySelector('.notification-menu');

    document.querySelector('.toggle-notification').addEventListener('click', toggleShowNotification);
    function toggleShowNotification(){
        notificationMainContainer.classList.toggle('show');
        notificationMenu.classList.toggle('active');
    }

    document.getElementById('close-notification').addEventListener('click',  closeFunc);
    notificationMainContainer.addEventListener('click', closeNotification);

    function closeNotification(e){
        e = window.event || e;
        if(this === e.target) {
            closeFunc();
        }
    }

    function closeFunc(){
        notificationMainContainer.classList.remove('show');
        notificationMenu.classList.remove('active');
    }
});
