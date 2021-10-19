@component('mail::message')
    # Update from MTN Developer Portal.

    Hi {{ $user->first_name }}

    You have been invited to the **{{ $team->name }}** team.

    @component('mail::button', ['url' => route('teams.invite.accept', ['token' => $user->getTeamInvite($team)->accept_token])])
        Accept Invite
    @endcomponent

    @component('mail::button', ['url' => route('teams.invite.deny', ['token' => $user->getTeamInvite($team)->deny_token])])
        Reject Invite
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
