@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="text-center p-4">
                    <img src="{{ asset('images/full_logo.png') }}" class="img-fluid" alt="Logo">
                </div>

                @error('login')
                <div class="row mb-0" style="padding-left: 12px; padding-right: 12px">
                    <span class="alert alert-danger">
                        <strong>{{ $message }}</strong>
                    </span>
                </div>
                @enderror
                
                @error('oauth')
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
                                           value="{{ old('username') }}" required autocomplete="username" autofocus>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <label for="password"
                                       class="col-lg-4 col-form-label text-lg-end">{{ __('Password') }}</label>

                                <div class="col-lg-8">
                                    <input id="password" type="password"
                                           class="form-control @error('password') is-invalid @enderror" name="password"
                                           required autocomplete="password">
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
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                        
                        <!-- OAuth Login Section -->
                        @if(config('oauth.enabled') && (config('services.google.client_id') || config('services.github.client_id') || config('services.discord.client_id')))
                            <div class="oauth-section mt-4">
                                <div class="text-center mb-3">
                                    <span class="text-muted">Or continue with</span>
                                </div>
                                
                                <!-- Invite Code Input (for invite-only mode) -->
                                @if(config('oauth.registration.mode') === 'invite_only')
                                    <div class="mb-3">
                                        <label for="invite_code" class="form-label text-muted small">Invitation Code</label>
                                        <input type="text" class="form-control form-control-sm" id="invite_code" name="invite_code" 
                                               placeholder="Enter your invitation code" value="{{ request('invite_code') }}">
                                        <div class="form-text">Required for OAuth registration</div>
                                    </div>
                                @endif
                                
                                <div class="d-grid gap-2">
                                    @if(config('services.google.client_id') && config('oauth.providers.google.enabled'))
                                        <a href="{{ route('oauth.redirect', 'google') }}{{ config('oauth.registration.mode') === 'invite_only' && request('invite_code') ? '?invite_code=' . request('invite_code') : '' }}" 
                                           class="btn btn-{{ \App\Http\Controllers\Webpanel\OAuthController::getProviderButtonColor('google') }} btn-sm oauth-btn" data-provider="google">
                                            <i class="fab fa-google me-2"></i> Google
                                        </a>
                                    @endif
                                    @if(config('services.github.client_id') && config('oauth.providers.github.enabled'))
                                        <a href="{{ route('oauth.redirect', 'github') }}{{ config('oauth.registration.mode') === 'invite_only' && request('invite_code') ? '?invite_code=' . request('invite_code') : '' }}" 
                                           class="btn btn-{{ \App\Http\Controllers\Webpanel\OAuthController::getProviderButtonColor('github') }} btn-sm oauth-btn" data-provider="github">
                                            <i class="fab fa-github me-2"></i> GitHub
                                        </a>
                                    @endif
                                    @if(config('services.discord.client_id') && config('oauth.providers.discord.enabled'))
                                        <a href="{{ route('oauth.redirect', 'discord') }}{{ config('oauth.registration.mode') === 'invite_only' && request('invite_code') ? '?invite_code=' . request('invite_code') : '' }}" 
                                           class="btn btn-{{ \App\Http\Controllers\Webpanel\OAuthController::getProviderButtonColor('discord') }} btn-sm oauth-btn" data-provider="discord">
                                            <i class="fab fa-discord me-2"></i> Discord
                                        </a>
                                    @endif
                                </div>
                                
                                @if(config('oauth.registration.mode') === 'invite_only')
                                    <div class="text-center mt-2">
                                        <small class="text-muted oauth-notice" style="display: none;">Please enter an invitation code to enable OAuth login</small>
                                    </div>
                                @endif
                                
                                @if(config('oauth.registration.mode') === 'invite_only')
                                    <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const inviteInput = document.getElementById('invite_code');
                                        const oauthBtns = document.querySelectorAll('.oauth-btn');
                                        const oauthNotice = document.querySelector('.oauth-notice');
                                        
                                        function updateOAuthLinks() {
                                            const inviteCode = inviteInput.value.trim();
                                            const hasInviteCode = inviteCode.length > 0;
                                            
                                            oauthBtns.forEach(btn => {
                                                const provider = btn.dataset.provider;
                                                const baseUrl = `{{ url('/auth') }}/${provider}`;
                                                
                                                if (hasInviteCode) {
                                                    // Enable button
                                                    btn.href = `${baseUrl}?invite_code=${encodeURIComponent(inviteCode)}`;
                                                    btn.classList.remove('disabled');
                                                    btn.style.pointerEvents = 'auto';
                                                    btn.style.opacity = '1';
                                                    btn.removeAttribute('tabindex');
                                                    btn.removeAttribute('aria-disabled');
                                                } else {
                                                    // Disable button
                                                    btn.href = '#';
                                                    btn.classList.add('disabled');
                                                    btn.style.pointerEvents = 'none';
                                                    btn.style.opacity = '0.6';
                                                    btn.setAttribute('tabindex', '-1');
                                                    btn.setAttribute('aria-disabled', 'true');
                                                }
                                            });
                                            
                                            // Show/hide notice
                                            if (oauthNotice) {
                                                oauthNotice.style.display = hasInviteCode ? 'none' : 'block';
                                            }
                                        }
                                        
                                        inviteInput.addEventListener('input', updateOAuthLinks);
                                        updateOAuthLinks(); // Initial update
                                    });
                                    </script>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
