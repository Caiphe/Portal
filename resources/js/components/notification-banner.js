var notificationBanner = document.getElementById('notification-banner');

window.addEventListener('load', hideNotificationBanner);
function hideNotificationBanner(){
    var notificationBannerData = localStorage.getItem('notification-banner-expiry');
    var now = new Date();

    if(notificationBannerData && now.getTime() < notificationBannerData){
        notificationBanner.classList.add('hide');
        return;
    }

    localStorage.removeItem(notificationBannerData);
    notificationBanner.classList.remove('hide');
}

var closeNotificationBtn = document.getElementById('close-banner-btn');
if(closeNotificationBtn){
    closeNotificationBtn.addEventListener('click', closeBanner);
}

function closeBanner(){
    notificationBanner.classList.add('hide');
    localStorage.removeItem('notification-banner-storage');
    setItemWithExpiry('notification-banner-expiry', 12 * 60 * 60 * 1000);
}

function setItemWithExpiry(key, ttl) {
    var now = new Date();
    localStorage.setItem(key, now.getTime() + ttl);
}
