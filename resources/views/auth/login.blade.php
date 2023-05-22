@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                @error('login')
                <div class="row mb-0" style="padding-left: 12px; padding-right: 12px">
                    <span class="alert alert-danger">
                        <strong>{{ $message }}</strong>
                    </span>
                </div>
                @enderror
                <div class="card">
                    <div class="card-header">{{ __('Login') }}</div>

                    <div class="card-body">
                        <form action="{{ route('login') }}" method="post">
                            @csrf

                            <div class="row mb-2">
                                <label for="username"
                                       class="col-lg-4 col-form-label text-lg-end">{{ __('Username') }}</label>

                                <div class="col-lg-8">
                                    <input id="username" type="text"
                                           class="form-control @error('username') is-invalid @enderror" name="username"
                                           value="{{ old('username') }}" required autocomplete="email" autofocus>

                                </div>
                            </div>

                            <div class="row mb-2">
                                <label for="password"
                                       class="col-lg-4 col-form-label text-lg-end">{{ __('Password') }}</label>

                                <div class="col-lg-8">
                                    <input id="password" type="password"
                                           class="form-control @error('password') is-invalid @enderror" name="password"
                                           required autocomplete="current-password">
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-8 offset-lg-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember"
                                               id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-lg-8 offset-lg-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Login') }}
                                    </button>

                                    @if (Route::has('password.request'))
                                        <a class=" btn btn-link
                                        " href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
