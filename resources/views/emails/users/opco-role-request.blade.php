@component('mail::message')
# A user has requested an Opco admin role, below are the user's detais:

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
