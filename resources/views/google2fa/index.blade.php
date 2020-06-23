@extends('layouts.master')

@push('styles')
<style>
    #form-2fa {
        width: 300px;
        text-align: center;
        display: flex;
        flex-direction: column;
        margin: 0 auto;
    }

    #form-2fa input {
        margin-bottom: 20px;
    }
</style>
@endpush

@section('content')
<form id="form-2fa" action="{{ route('user.2fa.verify') }}" method="POST">
    <img src="/images/illustrations/2fa.svg" alt="2FA Illustration">
    @csrf
    <input name="one_time_password" type="text" placeholder="Add authenticator code">
    <button type="submit">Authenticate</button>
</form>
@endsection