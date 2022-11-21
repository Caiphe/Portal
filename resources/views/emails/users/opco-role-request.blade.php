@component('mail::message')
# A user has requested an Opco admin role, below are user's details and countries requested:

|                                      |    |           |
|:----------------------------|:---|:----------|
|First Name                            |    | **{{ $user->first_name }}** |
|Last Name                             |    | **{{ $user->last_name }}** |
|Email                                 |    | **{{ $user->email }}** |
|Countries                             |    | @foreach ($countries as $country) {{ $country }}, @endforeach |


@component('mail::button', ['url' => route('admin.task.index')])
Proceed to task panel
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
