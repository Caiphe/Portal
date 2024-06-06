@if($maintenanceData)
<div class="notification-banner animated" id="notification-banner">
    <div class="banner-content">
        <img src="images/warning-white.svg">
        <span>{{ $maintenanceData->message }}</span>
    </div>
    <button id="close-banner-btn" class="outline white" type="button">Close</button>
</div>
@endif
