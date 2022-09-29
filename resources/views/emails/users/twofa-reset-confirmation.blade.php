@component('mail::message')
# 2fa reset request confirmation 

You are now able to set up your 2fa please click on below button:


@component('mail::button', ['url' => route('login')])
    Proceed
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
