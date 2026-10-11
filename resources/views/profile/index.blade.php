@extends('layouts.app')

@section('content')
@php
    $user = Auth::user();

    // Initials for the avatar (first letters of the first two words of the name)
    $initials = collect(preg_split('/\s+/', trim($user->name)))
        ->filter()
        ->take(2)
        ->map(fn ($word) => mb_strtoupper(mb_substr($word, 0, 1)))
        ->implode('');
@endphp

<style>
    .hero { background: linear-gradient(135deg, #0d6efd 0%, #0a4fc4 100%); }
    .avatar-lg {
        width: 5rem;
        height: 5rem;
        font-size: 1.75rem;
    }
</style>

<div class="container py-4">

    {{-- Page header --}}
    <div class="mb-4">
        <h1 class="h3 fw-bold mb-1">My profile</h1>
        <p class="text-secondary mb-0">Manage your account details and password.</p>
    </div>

    <div class="row g-4">

        {{-- Left: profile summary --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="hero" style="height: 5rem;"></div>
                <div class="card-body text-center px-4 pb-4" style="margin-top: -2.5rem;">
                    <div class="avatar-lg rounded-circle bg-white border border-4 border-white shadow-sm d-inline-flex align-items-center justify-content-center fw-bold text-primary">
                        {{ $initials }}
                    </div>

                    <h2 class="h5 fw-bold mt-3 mb-1">{{ $user->name }}</h2>
                    <p class="text-secondary mb-3">{{ $user->email }}</p>

                    <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary">Organizer account</span>

                    <hr class="my-4">

                    <div class="text-start small">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-secondary">Member since</span>
                            <span class="fw-semibold">{{ $user->created_at?->format('F Y') ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary">Last updated</span>
                            <span class="fw-semibold">{{ $user->updated_at?->diffForHumans() ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: forms --}}
        <div class="col-lg-8">

            {{-- Profile information --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h2 class="h5 fw-bold mb-1">Profile information</h2>
                    <p class="text-secondary mb-4">Update your name and email address.</p>

                    @if (session('status') === 'profile-updated')
                        <div class="alert alert-success" role="alert">Your profile has been updated.</div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Name') }}</label>
                            <input id="name" type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}" required autocomplete="name">

                            @error('name')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                            <input id="email" type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}" required autocomplete="email">

                            @error('email')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>

            {{-- Change password --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="h5 fw-bold mb-1">Change password</h2>
                    <p class="text-secondary mb-4">Use a long, unique password to keep your account secure.</p>

                    @if (session('status') === 'password-updated')
                        <div class="alert alert-success" role="alert">Your password has been changed.</div>
                    @endif

                    <form method="POST" action="{{ route('profile.password') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current password</label>
                            <input id="current_password" type="password" name="current_password"
                                   class="form-control {{ $errors->updatePassword->has('current_password') ? 'is-invalid' : '' }}"
                                   required autocomplete="current-password">

                            @error('current_password', 'updatePassword')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="new_password" class="form-label">New password</label>
                                <input id="new_password" type="password" name="password"
                                       class="form-control {{ $errors->updatePassword->has('password') ? 'is-invalid' : '' }}"
                                       required autocomplete="new-password">

                                @error('password', 'updatePassword')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="new_password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                                <input id="new_password_confirmation" type="password" name="password_confirmation"
                                       class="form-control" required autocomplete="new-password">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update password</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection