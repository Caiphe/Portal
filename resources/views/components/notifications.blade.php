<meta class="csrf-token" name="_token" content="{{ csrf_token() }}">

<div class="notification-main-container" id="notification-main-container">
    <div class="notification-content">
        <div class="top-buttons">
            <form method="POST" action="{{ route('notification.clear.all') }}" class="clear-all-notification">
                @csrf
                <button type="submit" class="text-button @if(!$notifications->count()) non-active @endif">Clear notifiations</button>
            </form>

            <form method="POST" action="{{ route('notification.read.all') }}" class="mark-read-all-form">
                @csrf
                <button type="submit" id="mark-read-all-button" class="text-button @if(!$notifications->count()) non-active @endif" href="">Mark all as read</button>
            </form>

            <button type="button" class="close-notification" id="close-notification">@svg('close', '#00678F')</button>
        </div>

        <div class="no-notifications @if(!$notifications->count()) show @endif" id="no-notifications">You have no notifications</div>
        <div class="notification-list" id="second-container"></div>

        {{-- @if($notifications->count() > 0)
            <div class="end-of-notification">End of notifications</div>
        @endif --}}

    </div>
</div>

