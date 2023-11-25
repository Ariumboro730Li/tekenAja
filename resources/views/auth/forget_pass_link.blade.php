@extends('layouts.app')

@section('content')
    <main class="login-form">
        <div class="cotainer">
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">Reset Password</div>
                        <div class="card-body">

                            <form action="{{ route('reset.password.post') }}" method="POST">
                                @csrf
                                <input type="hidden" class="form-control" name="token" value="{{ $token }}">

                                <div class="mb-3">
                                    <label for="" class="form-label">Email</label>
                                    <input type="text" name="email" id="email_address" class="form-control"aria-describedby="helpId" required autofocus>
                                    @if ($errors->has('email'))
                                            <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Password</label>
                                    <input type="password" id="password" class="form-control" name="password" aria-describedby="helpId" required autofocus>
                                    @if ($errors->has('password'))
                                            <span class="text-danger">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Confirm Password</label>
                                    <input type="password" id="password-confirm" class="form-control" name="password_confirmation" aria-describedby="helpId" required autofocus>
                                    @if ($errors->has('password_confirmation'))
                                            <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                                    @endif
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-outline-danger col-12">
                                        Reset Password
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
