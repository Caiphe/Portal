@component('mail::message')
    # Update from MTN Developer Portal.

    Hi {{ $user->first_name }}

    You have been invited to the **{{ $team->name }}** team.

    @component('mail::button', ['url' => \URL::signedRoute('company.join', ['company' => $company->username, 'user' => $user->username])])
        Accept Invite
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
