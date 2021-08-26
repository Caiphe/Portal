@component('mail::message')
    # Update from MTN Developer Portal.

    Hi {{ $user->first_name }}

    You have been removed from the **{{ $team->name }}** team.

    @component('mail::button', ['url' => route('user.profile')])
        View Profile
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
