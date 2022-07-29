(function(){
    var notificationMainContainer = document.getElementById('notification-main-container');
    var markAsReadButtons = document.querySelectorAll('.mark-as-read');
    var notificationMenu = document.querySelector('.notification-menu');
    var toggleReadForm = document.querySelectorAll('.read-unread-form');

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
    
    for(var i = 0; i < toggleReadForm.length; i++){
        toggleReadForm[i].addEventListener('submit', toggleReadFunc);
    }

    function toggleReadFunc(event){ 
        event.preventDefault();

        var notification = this.elements['notification'].value;
        var formToken = this.elements['_token'].value;
        var reat_at = this.elements['read_at'].value;

        var notificationData = {
            notification: notification,
            _method: 'POST',
            _token: formToken,
        };

        var xhr = new XMLHttpRequest();

        addLoading('marking as read...');

        xhr.open('POST', this.action);
        xhr.setRequestHeader('X-CSRF-TOKEN', formToken);
        xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.send(JSON.stringify(notificationData));


        xhr.onload = function() {
            removeLoading();
            if (xhr.status === 200) {

                var readMark = '';
                if(reat_at === ''){
                    readMark = 'read';
                }else{
                    readMark = 'unread';
                }

                addAlert('success', [`Notification marked as ${readMark}.`]);
                return;
            
            } else {
                var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;
                if(result.errors) {
                    result.message = [];
                    for(var error in result.errors){
                        result.message.push(result.errors[error]);
                    }
                }

                addAlert('error', result.message || 'Sorry there was a problem with your opco admin request. Please try again.');
            }
        };
    }
});