var notificationMainContainer = document.getElementById('notification-main-container');
var notificationsContainer = document.querySelector('#second-container');
var notificationRedDot = document.querySelector('#notification-red-dot');
var notificationFrontCount = document.querySelector('.front-notification-count');
var toggleNotificationMobileBtn = document.querySelector('#notification-btn');

document.getElementById('close-notification').addEventListener('click',  closeFunc);
notificationMainContainer.addEventListener('click', closeNotification);
toggleNotificationMobileBtn.addEventListener('click', showNotificationContainer);

function showNotificationContainer(){
    notificationMainContainer.classList.remove('hide');
    notificationMainContainer.classList.add('display');
}

function closeNotification(e){
    e = window.event || e;
    if(this === e.target) {
        closeFunc();
    }
}

function closeFunc(){
    notificationMainContainer.classList.add('hide');
    notificationMainContainer.classList.remove('display');
    window.location.reload();
}


fetch('/notifications/fetch-all').then(function(data) {
    return data.json();
}).then(function(notifications){
    var content = "";
    var entries = notifications.notifications;

    if(entries.lenth === 0){
        document.querySelector('#no-notifications').classList.add('show');
        return;
    }

    if(entries) {
        notificationsContainer.classList.remove('hide');
    }

    entries.map(function(values){
    content += `
        <div class="single-notification ${values.read_at ? 'read' : ''}">
            <p class="notification-message">${values.notification}</p>
            <div class="more-details">
                <span class="date-time">${values.created_at}</span>
                <button type="sbmit" data-status="${values.read_at ? 'unread' : 'read'}"
                        data-url ="/notification/${values.id}/read"
                        data-notification="${values.id}" onclick="toggleRead(this);" 
                        class="mark-as-read new-read">
                </button>
            </div>
        </div>
    `;
    });
    notificationsContainer.innerHTML = content;

}).catch(function(error){
    console.log("Error here");
});

var toggleReadForm = document.querySelectorAll('.read-unread-form');

function toggleRead(e){
    var notification = e.dataset.notification;
    var url = e.dataset.url;
    var formToken = document.querySelector('meta[name="csrf-token"]').content;
    e.closest('.single-notification').classList.toggle('read'); 
   
    var notificationData = {
        notification: notification,
        _method: 'POST',
        _token: formToken
    };

    var xhr = new XMLHttpRequest();

    xhr.open("POST", url, true);
    xhr.setRequestHeader('X-CSRF-TOKEN', formToken);
    xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    xhr.send(JSON.stringify(notificationData));

    xhr.onload = function() {
        if (xhr.status === 200) {

            var noteState = '';

            if(e.closest('.single-notification').classList.contains('read')) {

                if(notificationRedDot){
                    notificationFrontCount.value = Number(notificationFrontCount.value) - 1;

                    if(Number(notificationFrontCount.value) < 1){
                        notificationRedDot.classList.remove('show');
                    }
                }

                noteState = 'read';

            }else{
  
                if(notificationRedDot){
                    notificationFrontCount.value = Number(notificationFrontCount.value) + 1;

                    if(notificationFrontCount.value > 0){
                        notificationRedDot.classList.add('show');
                    }
                }
                noteState = 'unread';
            }

            addAlert('success', [`Notification marked as ${noteState}.`]);
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
            var allNotifications = document.querySelectorAll('.single-notification');
            
            for(var i = 0; i < allNotifications.length; i++){
                if(allNotifications[i].classList.contains('read')) continue;
                allNotifications[i].classList.add('read');
            }

            notificationRedDot.classList.remove('show');
            addAlert('success', [`All notifications marked as read.`]);

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
document.querySelector('.clear-all-notification').addEventListener('submit', clearAll);
function clearAll(ev){
    ev.preventDefault();

    var formToken = this.elements['_token'].value;
    var activeBtn = document.querySelectorAll('.text-button');

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

            notificationsContainer.classList.add('hide');
            document.querySelector('#no-notifications').classList.add('show');
            
            for(var i= 0; i < activeBtn.length; i++){
                activeBtn[i].classList.add('non-active');
            }

            notificationRedDot.classList.remove('show');
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

window.addEventListener('load', function(){
    fetch('/notifications/count').then(function(data) {
        return data.json();
    }).then(function(notifications){
        var count = notifications.count;
        var notificationDot = document.querySelector('.notification-red-dot');
        var notificationFrontCount = document.querySelector('.front-notification-count');
        notificationFrontCount.value = 0;

        if(count > 0){
            notificationDot.classList.add('show');
            notificationFrontCount.value = count;
        }
    
    }).catch(function(error){
        console.log(error);
    });
})
