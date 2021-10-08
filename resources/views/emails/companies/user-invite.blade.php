@component('mail::message')
    # Update from MTN Developer Portal.

    Hi {{ $user->first_name }}

    You have been invited to the **{{ $team->name }}** team.

    @component('mail::button', ['url' => route('register')])
        Accept Invite
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
