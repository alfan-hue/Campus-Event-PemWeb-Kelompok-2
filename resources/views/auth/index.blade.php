@extends('layouts.app')

@section('content')
@php
    $isRegister = request()->routeIs('register');
    $isLogin = ! $isRegister;
@endphp

<style>
    .hero-light {
        background: linear-gradient(135deg, #e7f1ff 0%, #f5f9ff 100%);
        border: 1px solid #cfe2ff;
    }
    .icon-circle { width: 2.5rem; height: 2.5rem; flex-shrink: 0; }
    .auth-tabs .nav-link { color: #0d6efd; font-weight: 600; }
    .auth-tabs .nav-link.active { color: #fff; }
</style>

<div class="container py-4">
    <div class="hero-light rounded-4 shadow-sm">
        <div class="row align-items-center g-4 g-lg-5 ps-5">

            {{-- Left: welcome text --}}
            <div class="col-lg-6">
                <span class="badge bg-primary text-white mb-3">Campus Event account</span>
                <h1 class="fw-bold mb-3">Share and manage your campus events</h1>
                <p class="text-secondary mb-4">
                    Log in or create an account to publish events for your organization and reach students across campus.
                </p>

                <div class="d-flex gap-3 mb-3">
                    <div class="icon-circle d-flex align-items-center justify-content-center rounded-circle bg-primary text-white fw-bold">1</div>
                    <div>
                        <h2 class="h6 fw-semibold mb-1">Create an account</h2>
                        <p class="small text-secondary mb-0">Register with your organization's details.</p>
                    </div>
                </div>
                <div class="d-flex gap-3 mb-3">
                    <div class="icon-circle d-flex align-items-center justify-content-center rounded-circle bg-primary text-white fw-bold">2</div>
                    <div>
                        <h2 class="h6 fw-semibold mb-1">Add your events</h2>
                        <p class="small text-secondary mb-0">Fill in the schedule, location, and registration link.</p>
                    </div>
                </div>
                <div class="d-flex gap-3 mb-4">
                    <div class="icon-circle d-flex align-items-center justify-content-center rounded-circle bg-primary text-white fw-bold">3</div>
                    <div>
                        <h2 class="h6 fw-semibold mb-1">Reach students</h2>
                        <p class="small text-secondary mb-0">Your event appears in search and category filters.</p>
                    </div>
                </div>

                @if (Route::has('organizers.guide'))
                    <a href="{{ route('organizers.guide') }}" class="link-primary fw-semibold text-decoration-none">
                        Read the organizer guide &rarr;
                    </a>
                @endif
            </div>

            {{-- Right: Login / Register card --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow rounded-4">
                    <div class="card-body p-4">

                        {{-- Tabs (switched with jQuery) --}}
                        <div class="nav nav-pills nav-fill auth-tabs bg-light rounded-3 p-1 mb-4" role="tablist">
                            <button type="button" class="nav-link auth-tab {{ $isLogin ? 'active' : '' }}"
                                    data-tab="login" data-url="{{ route('login', [], false) }}"
                                    role="tab" aria-selected="{{ $isLogin ? 'true' : 'false' }}">
                                {{ __('Login') }}
                            </button>
                            <button type="button" class="nav-link auth-tab {{ $isRegister ? 'active' : '' }}"
                                    data-tab="register" data-url="{{ route('register', [], false) }}"
                                    role="tab" aria-selected="{{ $isRegister ? 'true' : 'false' }}">
                                {{ __('Register') }}
                            </button>
                        </div>

                        {{-- LOGIN --}}
                        <div class="auth-pane" id="login-pane" role="tabpanel" @unless ($isLogin) style="display: none;" @endunless>
                            <h2 class="h4 fw-bold mb-1">Welcome back</h2>
                            <p class="text-secondary mb-4">Log in to manage your events.</p>

                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="mb-3">
                                    <label for="login-email" class="form-label">{{ __('Email Address') }}</label>
                                    <input id="login-email" type="email"
                                           class="form-control form-control-lg {{ $isLogin && $errors->has('email') ? 'is-invalid' : '' }}"
                                           name="email" value="{{ $isLogin ? old('email') : '' }}"
                                           required autocomplete="email" @if ($isLogin) autofocus @endif>

                                    @if ($isLogin)
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="login-password" class="form-label">{{ __('Password') }}</label>
                                    <input id="login-password" type="password"
                                           class="form-control form-control-lg {{ $isLogin && $errors->has('password') ? 'is-invalid' : '' }}"
                                           name="password" required autocomplete="current-password">

                                    @if ($isLogin)
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    @endif
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">{{ __('Remember Me') }}</label>
                                    </div>

                                    @if (Route::has('password.request'))
                                        <a class="small text-decoration-none" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">{{ __('Login') }}</button>
                                </div>
                            </form>

                            <p class="text-center text-secondary small mt-4 mb-0">
                                New here?
                                <a href="#" class="text-decoration-none" data-switch-tab="register">Create an account</a>
                            </p>
                        </div>

                        {{-- REGISTER --}}
                        <div class="auth-pane" id="register-pane" role="tabpanel" @unless ($isRegister) style="display: none;" @endunless>
                            <h2 class="h4 fw-bold mb-1">Create your account</h2>
                            <p class="text-secondary mb-4">Register to start sharing events.</p>

                            <form method="POST" action="{{ route('register') }}">
                                @csrf

                                <div class="mb-3">
                                    <label for="register-name" class="form-label">{{ __('Name') }}</label>
                                    <input id="register-name" type="text"
                                           class="form-control form-control-lg @error('name') is-invalid @enderror"
                                           name="name" value="{{ old('name') }}" placeholder="Your name or organization"
                                           required autocomplete="name" @if ($isRegister) autofocus @endif>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="register-email" class="form-label">{{ __('Email Address') }}</label>
                                    <input id="register-email" type="email"
                                           class="form-control form-control-lg {{ $isRegister && $errors->has('email') ? 'is-invalid' : '' }}"
                                           name="email" value="{{ $isRegister ? old('email') : '' }}"
                                           required autocomplete="email">

                                    @if ($isRegister)
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    @endif
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="register-password" class="form-label">{{ __('Password') }}</label>
                                        <input id="register-password" type="password"
                                               class="form-control form-control-lg {{ $isRegister && $errors->has('password') ? 'is-invalid' : '' }}"
                                               name="password" required autocomplete="new-password">

                                        @if ($isRegister)
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        @endif
                                    </div>

                                    <div class="col-md-6">
                                        <label for="register-password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                                        <input id="register-password-confirm" type="password"
                                               class="form-control form-control-lg"
                                               name="password_confirmation" required autocomplete="new-password">
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">{{ __('Register') }}</button>
                                </div>
                            </form>

                            <p class="text-center text-secondary small mt-4 mb-0">
                                Already have an account?
                                <a href="#" class="text-decoration-none" data-switch-tab="login">Log in</a>
                            </p>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function () {
        (function ($) {
            if (!$) { return; }

            function switchTab(name) {
                var $tab = $('.auth-tab[data-tab="' + name + '"]');

                $('.auth-tab').removeClass('active').attr('aria-selected', 'false');
                $tab.addClass('active').attr('aria-selected', 'true');

                $('.auth-pane').stop(true, true).hide();
                $('#' + name + '-pane').fadeIn(150, function () {
                    $(this).find('input:visible:first').trigger('focus');
                });

                history.replaceState(null, '', $tab.data('url'));
            }

            $('.auth-tab').on('click', function () {
                switchTab($(this).data('tab'));
            });

            $('[data-switch-tab]').on('click', function (e) {
                e.preventDefault();
                switchTab($(this).data('switch-tab'));
            });
        })(window.jQuery);
    });
</script>
@endsection