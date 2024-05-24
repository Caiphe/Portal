@php
    $count = count($countries);
@endphp

@component('mail::message')
# A user deletion request has been made, below are user's details:

|                                      |    |           |
|:----------------------------|:---|:----------|
|First Name                            |    | **{{ $user->first_name }}** |
|Last Name                             |    | **{{ $user->last_name }}** |
|Email                                 |    | **{{ $user->email }}** |
@if($countries) |Country                               |    | @foreach ($countries as $key => $country) {{ $country }} @if ($count > 1 && $key < $count - 1) , @endif @endforeach | @endif


@component('mail::button', ['url' => route('admin.user.edit', $user)])
    Proceed to user profile
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
