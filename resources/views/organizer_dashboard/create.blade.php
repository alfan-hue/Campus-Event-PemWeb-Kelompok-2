@extends('layouts.app')

@section('content')

<div class="container py-4">
    <div class="row g-4">

        <div class="col-lg-3">
            @include('organizer_dashboard.sidebar')
        </div>

        <div class="col-lg-9">

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="{{ url('/organizer') }}" class="text-decoration-none">My events</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add event</li>
                </ol>
            </nav>

            <div class="mb-4">
                <h1 class="h3 fw-bold mb-1">Add event</h1>
                <p class="text-secondary mb-0">Fill in the details students need to find and join your event.</p>
            </div>

            @include('organizer_dashboard.form', [
                'action' => url('/organizer/events'),
                'method' => 'POST',
                'event' => null,
                'categories' => $categories,
                'submitLabel' => 'Publish event',
            ])

        </div>
    </div>
</div>
@endsection
