@component('mail::message')
# Your profile has been updated

Below are your new profile details:

|                   |    |           |
|:------------------|:---|:----------|
|First Name         |    | **{{ $user->first_name }}** |
|Last Name          |    | **{{ $user->last_name }}** |
|Email              |    | **{{ $user->email }}** |
|Locations          |    | **{{ $user->countries->pluck('name')->implode(', ') }}** |


@component('mail::button', ['url' => route('user.profile', $user)])
View your profile
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
