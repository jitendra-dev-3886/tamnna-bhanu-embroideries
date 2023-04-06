@extends('layouts.app')

@section('content')
    <div class="container reset-password-section">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center">{{ __('Reset Password') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('password.reset') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ Request::get('token') }}">

                            <div class="form-group row mb-3">
{{--                                <label for="email" class="col-md-4 col-form-label text-md-right mt-4">{{ __('E-Mail Address') }}</label>--}}
                                <div class="col-md-3"></div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <span class="highlight"></span>
                                        <span class="bar"></span>
                                        <label>Email Id</label>
                                    </div>

                                </div>
                                <div class="col-md-3"></div>
                            </div>

                            <div class="form-group row mb-3">
{{--                                <label for="password" class="col-md-4 col-form-label text-md-right mt-4">{{ __('Password') }}</label>--}}
                                <div class="col-md-3"></div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                        <span class="highlight"></span>
                                        <span class="bar"></span>
                                        <label>Password</label>
                                    </div>
                                </div>
                                <div class="col-md-3"></div>
                            </div>

                            <div class="form-group row mb-3">
{{--                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right mt-4">{{ __('Confirm Password') }}</label>--}}
                                <div class="col-md-3"></div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                        <span class="highlight"></span>
                                        <span class="bar"></span>
                                        <label>Confirm Password</label>
                                    </div>
                                </div>
                                <div class="col-md-3"></div>
                            </div>

                            <div class="form-group row mb-0 mb-3">
                                <div class="col-md-3"></div>
                                <div class="col-md-6 col-xs-12 text-center">
                                    <button type="submit" class="btn btn-primary text-uppercase">
                                        {{ __('Reset Password') }}
                                    </button>
                                </div>
                                <div class="col-md-3"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
