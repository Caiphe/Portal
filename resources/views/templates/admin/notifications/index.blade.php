@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/notifications/index.css') }}">
@endpush

<div class="notification-main-container">
    <div class="notification-content">
        <div class="top-buttons">
            <button>Clear notifiations</button>
            <button>Mark all as read</button>
            <button>@svg('close', '#00678F')</button>
        </div>

        <div class="notification-list">

            <div class="single-notification">
                <p class="notification-message">Your application “Customer Details Management V1(Staging)” has been revoked. please see provided detail for reason of revoking</p>
                <div class="more-details">
                    <span class="date-time">May 12, 13:15</span>
                    <button class="mark-as-read">Mark as read</button>
                </div>
            </div>

            <div class="single-notification">
                <p class="notification-message">Your application “Customer Details Management V1(Staging)” has been revoked. please see provided detail for reason of revoking</p>
                <div class="more-details">
                    <span class="date-time">May 12, 13:15</span>
                    <button class="mark-as-read">Mark as read</button>
                </div>
            </div>

            <div class="single-notification">
                <p class="notification-message">Your application “Customer Details Management V1(Staging)” has been revoked. please see provided detail for reason of revoking</p>
                <div class="more-details">
                    <span class="date-time">May 12, 13:15</span>
                    <button class="mark-as-read">Mark as read</button>
                </div>
            </div>
            
            <div class="single-notification">
                <p class="notification-message">Your application “Customer Details Management V1(Staging)” has been revoked. please see provided detail for reason of revoking</p>
                <div class="more-details">
                    <span class="date-time">May 12, 13:15</span>
                    <button class="mark-as-read">Mark as read</button>
                </div>
            </div>

        </div>
    </div>
</div>