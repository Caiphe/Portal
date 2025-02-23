@php
    $count = count($countries);
@endphp

@component('mail::message')
# A user has requested 2FA reset

Below are user's details:

|                   |    |           |
|:------------------|:---|:----------|
|First Name         |    | **{{ $user->first_name }}** |
|Last Name          |    | **{{ $user->last_name }}** |
|Email              |    | **{{ $user->email }}** |
|OpCo(s)            |    | @foreach ($countries as $key => $country) {{ $country }} @if ($count > 1 && $key < $count - 1) , @endif @endforeach |

@component('mail::button', ['url' => route('admin.user.edit', $user)])
    Confirm 2FA reset
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
