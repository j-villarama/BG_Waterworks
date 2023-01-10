@extends('member.layouts.app')

@section('content')
<style>
    body{
        color: azure;
        background-color: #cee5ea;
        background-image: url('/image/bg-background.jpg');
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size: cover;
    background-position: center;
    }
    
    .card-body,.card-header{
        background: #0c5e5c;
       
    }
    
    </style>
<div class="container">
    <div class="row d-flex justify-content-center">
        <div class="col-md-8 d-flex justify-content-center">
            <div class="card" style="background-color: #09504f">
                <div class="card-header">
                    <div>{{ __('Reset Password') }}</div>
                    {{-- <div style="float: right;"><a href="{{ route('admin.dashboard') }}" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></a></div> --}}
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            {{-- <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-outline-success">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div> --}}
                            <div class="col-md-5 offset-md-3">
                                <button style="color:black; background-color: #cee5ea; border:#09504f" type="submit" class="btn btn-outline-success">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                            <div class="col">
                                <a style="color:black; background-color: #cee5ea; border:#09504f" href="{{ route('login') }}" class="btn btn-outline-danger"><i class='bx bx-arrow-back'></i>Return</a>
                            </div>
                        </div>

                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
