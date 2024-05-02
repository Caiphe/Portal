@if(is_null(auth()->user()['2fa']))
    <div {{ $attributes->merge(['class' => 'twofa-warning']) }}>
        Warning! Your account is not secure, set up Two Factor Authentication. Your account functionality will be
        limited until 2fa setup is complete.
        <a href="{{ route('user.profile') }}#twofa" class="button white outline">SET UP</a>
    </div>
@endif
