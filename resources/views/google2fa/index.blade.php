@extends('layouts.master')

@section('content')
<div>secret key</div>
<form action="{{ route('user.2fa.verify') }}" method="POST">
    @csrf
    <input name="one_time_password" type="text">
    <button type="submit">Authenticate</button>
</form>
@endsection