@push('styles')
<link rel="stylesheet" href="{{ mix('/css/components/cookie.css') }}">
@endpush

@push('scripts')
<div id="cookie-policy" transparent>
    <div class="cookie-policy-content">
        <h3>Your Privacy</h3>
        <p>
            By clicking <strong>Accept all cookies</strong> you agree to use all cookies to help improve your experience with personalised content.<br>
            Or click <strong>Cookie preferences</strong> to change cookies or withdraw consent. View the <a href="{{ route('page.show', 'cookie-policy') }}" target="_blank">Cookie Policy</a>
        </p>
        <button id="cookie-policy-preferences-button" class="button outline dark black">COOKIE PREFERENCES</button>
        <button id="cookie-policy-accept-button" class="button dark">ACCEPT COOKIES</button>
    </div>
</div>
<x-dialog id="cookie-policy-preferences">
    <h2>PRIVACY OVERVIEW</h2>
    <p>This website uses cookies to improve your experience while you navigate through the website. Out of these, the cookies that categorised as necessary are stored on your browser as they are essential for the working of basic functionalities.</p>
    <div id="cookie-policy-necessary" class="cookie-policy-option">
        <h3 class="cookie-policy-option-header">@svg('chevron-down') Necessary</h3>
        <p>Necessary cookies are absolutely essential for the website to function properly. This category only includes cookies that ensures basic functionalities and security features of the website. These cookies do not store any personal information.</p>
    </div>
    <div id="cookie-policy-analytics" class="cookie-policy-option">
        <h3 class="cookie-policy-option-header">@svg('chevron-down') Google Analytics</h3>
        <p>These cookies collect information that can help understand how the website is being used. This information can also be used to measure effectiveness in our marketing campaigns or to curate a personalized site experience for you.</p>
        <x-switch
            id="cookie-policy-analytics-switch"
            name="cookie-policy-analytics-switch"
            value="on"
            type="small"
            scheme="light"
            checked="checked"
        ></x-switch>
    </div>
    <button id="cookie-policy-accept" class="button outline">SAVE & ACCEPT</button>
</x-dialog>

<script src="{{ mix('/js/components/cookie.js') }}" defer></script>
@endpush