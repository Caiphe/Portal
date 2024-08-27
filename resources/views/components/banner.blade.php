@if($maintenanceData)
<input type="hidden" class="maintenance-exist" id="maintenance-exist" />
<div class="notification-banner" id="notification-banner">
    <div class="banner-content">
        <img src="/images/warning-white.svg">
        <span>{{ $maintenanceData->message }}</span>
    </div>
    <button id="close-banner-btn" class="outline white" type="button">Close</button>
</div>
@endif
