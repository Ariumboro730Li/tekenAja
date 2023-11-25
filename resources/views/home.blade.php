@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Welcome on Board') }}, {{Auth::user()->name}}</div>

                <div class="card-body text-center">
                    <p>
                        {{ __('You are logged in!') }}
                    </p>
                    <h3>
                        {{ strip_tags(\Illuminate\Foundation\Inspiring::quote()) }}

                    </h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
