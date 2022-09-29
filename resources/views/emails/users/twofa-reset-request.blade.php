@component('mail::message')
# A user has requested 2fa reset

Below are user's details:

|                   |    |           |
|:------------------|:---|:----------|
|First Name         |    | **{{ $user->first_name }}** |
|Last Name          |    | **{{ $user->last_name }}** |
|Email              |    | **{{ $user->email }}** |

@component('mail::button', ['url' => route('admin.user.edit', $user)])
    Confirm 2fA reset
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
