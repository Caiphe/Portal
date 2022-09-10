@component('mail::message')
# A user has requested 2Fa reset

Below are the user's details:

|                   |    |           |
|:------------------|:---|:----------|
|First Name         |    | **{{ $user->first_name }}** |
|Last Name          |    | **{{ $user->last_name }}** |
|Email              |    | **{{ $user->email }}** |

@component('mail::button', ['url' => route('admin.user.edit', $user)])
    Confirm reset 2FA
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
