@component('mail::message')
# Opco admin role request

Below are the user's details:

|                                      |    |           |
|:----------------------------|:---|:----------|
|First Name                            |    | **{{ $user->first_name }}** |
|Last Name                             |    | **{{ $user->last_name }}** |
|Email                                 |    | **{{ $user->email }}** |
|Countries                             |    | **{{ $user->countries->pluck('name')->implode(', ') }}** |


@component('mail::button', ['url' => route('admin.task.index')])
Proceed to the Task Panel
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
