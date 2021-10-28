@component('mail::message')
    # {{ $user->full_name }} has accepted your invite to join {{ $team->name }} team.

    Visit your Team's page to see a refreshed list of your **Team** *members*.

    @component('mail::button', [ 'url' => route('team.show', $team->id) ])
        Go to Team
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
s
