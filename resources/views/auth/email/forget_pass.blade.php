@extends('layouts.app')
@section('content')
<div class="container text-center">
    <h1>Forget Password Email</h1>

    You can reset password from bellow link:
    <a href="{{ route('reset.password.get', $token) }}">Reset Password</a>
</div>
@endsection
