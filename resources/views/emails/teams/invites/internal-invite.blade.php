@component('mail::message')
# Update from MTN Developer Portal.

Hi {{ $invitee->first_name }}

You have been invited to the **{{ $team->name }}** team. Please go to your profile to respond to your invite.

@component('mail::button', [ 'url' => route('app.index') ])
    Complete action
@endcomponent

Thanks,  
{{ config('app.name') }}
@endcomponent
