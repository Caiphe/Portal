@component('mail::message')
# Update from MTN Developer Portal.

Hi {{ $invitee->first_name }}

You have been requested to be the owner of **{{ $team->name }}** team.

@component('mail::button', [ 'url' => route('team.show', $team->id) ])
    Complete Action
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
