@component('mail::message')
# Renew your credentials

To renew your credentials for {{ $app->display_name }}, please click on the link below.

@component('mail::button', ['url' => url()->signedRoute('app.credentials.renew', ['app' => $app, 'type' => $type])])
Renew Credentials
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
