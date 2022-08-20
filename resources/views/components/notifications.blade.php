@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/notifications/index.css') }}">
@endpush
<meta class="csrf-token" name="_token" content="{{ csrf_token() }}">

<div class="notification-main-container" id="notification-main-container">
    <div class="notification-content">
        <div class="top-buttons">
            <form method="POST" action="{{ route('notification.clear.all') }}" class="clear-all-notification">
                @csrf
                <button type="submit">Clear notifiations</button>
            </form>

            <form method="POST" action="{{ route('notification.read.all') }}" class="mark-read-all-form">
                @csrf
                <button type="submit" href="">Mark all as read</button>
            </form>

            <button type="button" class="close-notification" id="close-notification">@svg('close', '#00678F')</button>
        </div>

        <div class="no-notifications" id="no-notifications">You have no notifications</div>

        <div style="display: block;" class="notification-list" id="second-container">
        </div>


    </div>
</div>

@push('scripts')
    <script>
        var notificationMainContainer = document.getElementById('notification-main-container');
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
        
       
        fetch('/admin/notifications/fetch-all').then(function(data) {
            return data.json();
        }).then(function(notifications){
            var content = "";
            var entries = notifications.notifications;
            var notificationsContainer = document.querySelector('#second-container');

            if(entries.lenth === 0){
                document.querySelector('#no-notifications').classList.add('show');
                return;
            }

            entries.map(function(values){
            content += `
                <div class="single-notification ${values.read_at ? 'read' : ''}">
                    <p class="notification-message">${values.notification}</p>
                    <div class="more-details">
                        <span class="date-time">${values.formattedDate}</span>
                        <button type="sbmit" data-status="${values.read_at ? 'unread' : 'read'}"
                                data-url ="/admin/notification/${values.id}/read"
                                data-notification="${values.id}" onclick="toggleRead(this);" 
                                class="mark-as-read new-read">
                        </button>
                    </div>
                </div>
            `;
            });
            notificationsContainer.innerHTML = content;

        }).catch(function(error){
            // console.log("Error here");
        });

        var toggleReadForm = document.querySelectorAll('.read-unread-form');

        function toggleRead(e){
            var notification = e.dataset.notification;
            var status = e.dataset.status;
            var url = e.dataset.url;
            var formToken = document.querySelector('meta[name="csrf-token"]').content;
            e.closest('.single-notification').classList.toggle('read'); 

            var notificationData = {
                notification: notification,
                _method: 'POST',
                _token: formToken
            };

            var xhr = new XMLHttpRequest();

            addLoading('marking as read...');

            xhr.open("POST", url, true);
            xhr.setRequestHeader('X-CSRF-TOKEN', formToken);
            xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.send(JSON.stringify(notificationData));

            xhr.onload = function() {
                removeLoading();
                if (xhr.status === 200) {

                    addAlert('success', [`Notification marked as ${status}.`]);
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

        // Mark all notifications unread as read
        document.querySelector('.mark-read-all-form').addEventListener('submit', readAllFunc);
        function readAllFunc(ev){
            ev.preventDefault();

            var formToken = this.elements['_token'].value;

            var notificationData = {
                _method: 'POST',
                _token: formToken,
            };

            var xhr = new XMLHttpRequest();

            addLoading('marking all notificationa as read...');

            xhr.open('POST', this.action);
            xhr.setRequestHeader('X-CSRF-TOKEN', formToken);
            xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.send(JSON.stringify(notificationData));

            xhr.onload = function() {
                removeLoading();
                if (xhr.status === 200) {

                    addAlert('success', [`Notifications marked read.`]);
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

        // Mark all notifications unread as read
        document.querySelector('.clear-all-notification').addEventListener('submit', readAllFunc);
        function readAllFunc(ev){
            ev.preventDefault();

            var formToken = this.elements['_token'].value;

            var notificationData = {
                _method: 'POST',
                _token: formToken,
            };

            var xhr = new XMLHttpRequest();

            addLoading('clearing all notifications...');

            xhr.open('POST', this.action);
            xhr.setRequestHeader('X-CSRF-TOKEN', formToken);
            xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.send(JSON.stringify(notificationData));

            xhr.onload = function() {
                removeLoading();
                if (xhr.status === 200) {

                    addAlert('success', [`All notifications cleared successfully.`]);
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

    </script>
@endpush