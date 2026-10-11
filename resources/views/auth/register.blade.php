@extends('layouts.app')

@php
    // /register opens the Register tab, anything else opens Login.
    // After a failed submit Laravel redirects back to the same URL, so the right tab stays open.
    $activeTab = request()->routeIs('register') ? 'register' : 'login';

    // True when the validation error belongs to the form in the given tab.
    $err = fn ($tab, $field) => $activeTab === $tab && $errors->has($field);
@endphp

@section('title', ($activeTab === 'register' ? __('Register') : __('Login')) . ' · Campus Event')

@push('styles')
<style>
    .hero-light {
        background: linear-gradient(135deg, #e7f1ff 0%, #f5f9ff 100%);
        border: 1px solid #cfe2ff;
    }
    .icon-circle { width: 2.5rem; height: 2.5rem; flex-shrink: 0; }
    .auth-tabs .nav-link { color: #0d6efd; font-weight: 600; }
    .auth-tabs .nav-link.active { color: #fff; }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="hero-light rounded-4 shadow-sm p-3 p-lg-5">
        <div class="row align-items-center g-4 g-lg-5">

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

                <a href="{{ route('organizers.guide') }}" class="link-primary fw-semibold text-decoration-none">
                    Read the organizer guide &rarr;
                </a>
            </div>

            {{-- Right: Login / Register card --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow">
                    <div class="card-body p-4 p-lg-5">

                        {{-- Tabs (switched with jQuery) --}}
                        <ul class="nav nav-pills nav-fill auth-tabs bg-light rounded-3 p-1 mb-4" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button type="button" id="login-tab" role="tab"
                                        class="nav-link auth-tab {{ $activeTab === 'login' ? 'active' : '' }}"
                                        data-tab="login" data-url="{{ route('login', [], false) }}"
                                        aria-controls="login-pane" aria-selected="{{ $activeTab === 'login' ? 'true' : 'false' }}">
                                    {{ __('Login') }}
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" id="register-tab" role="tab"
                                        class="nav-link auth-tab {{ $activeTab === 'register' ? 'active' : '' }}"
                                        data-tab="register" data-url="{{ route('register', [], false) }}"
                                        aria-controls="register-pane" aria-selected="{{ $activeTab === 'register' ? 'true' : 'false' }}">
                                    {{ __('Register') }}
                                </button>
                            </li>
                        </ul>

                        {{-- LOGIN --}}
                        <div id="login-pane" class="auth-pane {{ $activeTab === 'login' ? '' : 'd-none' }}" role="tabpanel" aria-labelledby="login-tab">
                            <h2 class="h4 fw-bold mb-1">Welcome back</h2>
                            <p class="text-secondary mb-4">Log in to manage your events.</p>

                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="mb-3">
                                    <label for="login-email" class="form-label">{{ __('Email Address') }}</label>
                                    <input id="login-email" type="email" name="email"
                                           class="form-control form-control-lg {{ $err('login', 'email') ? 'is-invalid' : '' }}"
                                           value="{{ $activeTab === 'login' ? old('email') : '' }}"
                                           required autocomplete="email" @if ($activeTab === 'login') autofocus @endif>

                                    @if ($err('login', 'email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="login-password" class="form-label">{{ __('Password') }}</label>
                                    <div class="input-group input-group-lg has-validation">
                                        <input id="login-password" type="password" name="password"
                                               class="form-control {{ $err('login', 'password') ? 'is-invalid' : '' }}"
                                               required autocomplete="current-password">
                                        <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#login-password" tabindex="-1">Show</button>

                                        @if ($err('login', 'password'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
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
                        <div id="register-pane" class="auth-pane {{ $activeTab === 'register' ? '' : 'd-none' }}" role="tabpanel" aria-labelledby="register-tab">
                            <h2 class="h4 fw-bold mb-1">Create your account</h2>
                            <p class="text-secondary mb-4">Register to start sharing events.</p>

                            <form method="POST" action="{{ route('register') }}" id="register-form">
                                @csrf

                                <div class="mb-3">
                                    <label for="register-name" class="form-label">{{ __('Name') }}</label>
                                    <input id="register-name" type="text" name="name"
                                           class="form-control form-control-lg {{ $err('register', 'name') ? 'is-invalid' : '' }}"
                                           value="{{ old('name') }}" placeholder="Your name or organization"
                                           required autocomplete="name" @if ($activeTab === 'register') autofocus @endif>

                                    @if ($err('register', 'name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="register-email" class="form-label">{{ __('Email Address') }}</label>
                                    <input id="register-email" type="email" name="email"
                                           class="form-control form-control-lg {{ $err('register', 'email') ? 'is-invalid' : '' }}"
                                           value="{{ $activeTab === 'register' ? old('email') : '' }}"
                                           required autocomplete="email">

                                    @if ($err('register', 'email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="register-password" class="form-label">{{ __('Password') }}</label>
                                    <div class="input-group input-group-lg has-validation">
                                        <input id="register-password" type="password" name="password"
                                               class="form-control {{ $err('register', 'password') ? 'is-invalid' : '' }}"
                                               required autocomplete="new-password">
                                        <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#register-password" tabindex="-1">Show</button>

                                        @if ($err('register', 'password'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="register-password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                                    <input id="register-password-confirm" type="password" name="password_confirmation"
                                           class="form-control form-control-lg" required autocomplete="new-password">
                                    <div class="invalid-feedback">Passwords do not match.</div>
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
@endsection

@push('scripts')
<script>
    $(function () {

        // ---- Switch between Login and Register ----
        function showTab(name) {
            var $tab = $('.auth-tab[data-tab="' + name + '"]');

            $('.auth-tab').removeClass('active').attr('aria-selected', 'false');
            $tab.addClass('active').attr('aria-selected', 'true');

            $('.auth-pane').addClass('d-none');
            $('#' + name + '-pane')
                .removeClass('d-none')
                .hide()
                .fadeIn(150, function () {
                    $(this).find('input:first').trigger('focus');
                });

            // Keep the address bar and page title in sync (/login or /register)
            history.replaceState(null, '', $tab.data('url'));
            document.title = $.trim($tab.text()) + ' · Campus Event';
        }

        $('.auth-tab').on('click', function () {
            showTab($(this).data('tab'));
        });

        $('[data-switch-tab]').on('click', function (e) {
            e.preventDefault();
            showTab($(this).data('switch-tab'));
        });

        // ---- Show / hide password ----
        $('.toggle-password').on('click', function () {
            var $input = $($(this).data('target'));
            var show = $input.attr('type') === 'password';

            $input.attr('type', show ? 'text' : 'password');
            $(this).text(show ? 'Hide' : 'Show');
        });

        // ---- Live "passwords match" check on Register ----
        var $password = $('#register-password');
        var $confirm  = $('#register-password-confirm');

        function checkConfirm() {
            if (!$confirm.val()) {
                $confirm.removeClass('is-invalid is-valid');
                return true;
            }
            var match = $password.val() === $confirm.val();
            $confirm.toggleClass('is-invalid', !match).toggleClass('is-valid', match);
            return match;
        }

        $password.add($confirm).on('input', checkConfirm);

        $('#register-form').on('submit', function (e) {
            if (!checkConfirm()) {
                e.preventDefault();
                $confirm.trigger('focus');
            }
        });
    });
</script>
@endpush