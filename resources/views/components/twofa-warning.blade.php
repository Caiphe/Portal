@if(is_null(auth()->user()['2fa']))
<div {{ $attributes->merge(['class' => 'twofa-warning']) }}>
    Warning! Your account is not secure, set up Two Factor Authentication.
    <a href="{{ route('user.profile') }}#twofa" class="button white outline">GET STARTED</a>
</div>
@endif