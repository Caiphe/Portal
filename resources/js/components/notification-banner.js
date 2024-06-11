var notificationBanner = document.getElementById('notification-banner');
var closeNotificationBtn = document.getElementById('close-banner-btn');

window.addEventListener('load', hideNotificationBanner);
function hideNotificationBanner(){
    var notificationBannerData = localStorage.getItem('notification-banner-expiry');
    var now = new Date();

    if(!closeNotificationBtn){
        localStorage.removeItem('notification-banner-expiry');
        return;
    }

    if(closeNotificationBtn && notificationBannerData && now.getTime() < notificationBannerData){
        notificationBanner.classList.add('hide');
        return;
    }
    
    if(notificationBannerData && now.getTime() > notificationBannerData){
        localStorage.removeItem('notification-banner-expiry');
        return;
    }
}

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
