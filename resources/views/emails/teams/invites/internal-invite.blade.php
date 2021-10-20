@component('mail::message')
    # Update from MTN Developer Portal.

    Hi {{ $invitee->first_name }}

    You have been invited to the **{{ $team->name }}** team. Please go to your profile to respond to your invite.

    @component('mail::button', [ 'url' => route('teams.invite.accept', [ 'token' => $tokens['access_token'] ]) ])
        Accept Invite
    @endcomponent

    @component('mail::button', ['url' => route('teams.invite.deny', [ 'token' => $tokens['deny_token'] ]) ])
        Reject Invite
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent

