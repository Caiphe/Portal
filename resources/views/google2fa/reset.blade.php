@extends('layouts.master')

@push('styles')
<style>
    #form-2fa {
        width: 300px;
        text-align: center;
        display: flex;
        flex-direction: column;
        margin: 90px auto 40px;
    }

    #form-2fa img{
        width: 256px;
        margin: 0 auto;
    }

    #form-2fa input {
        margin-bottom: 20px;
    }

    .reset-twofa{
        color: #00678F;
        font-size: 15px;
        margin-bottom: 80px;
        text-align: center;
        display: block;
    }

</style>
@endpush

@section('content')
<form id="form-2fa" action="{{ route('user.2fa.verify') }}" method="POST">
    <img src="/images/illustrations/2fa.svg" alt="2FA Illustration" width="256" height="354">
    @csrf
    <input name="one_time_password" type="text" placeholder="Enter authenticator code" required autocomplete="off" autofocus>
    <button type="submit">Authenticate</button>
</form>

<a class="reset-twofa" href="">Can’t access your authentification device?</a>

@endsection