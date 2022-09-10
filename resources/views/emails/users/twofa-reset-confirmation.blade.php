@component('mail::message')
# 2FA reset confirmation 

You are now able to set up your Two Fa please click on below button:


@component('mail::button', ['url' => route('login')])
    Proceed
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
