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
                    <li class="breadcrumb-item active" aria-current="page">Edit {{ $event->title }}</li>
                </ol>
            </nav>

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                <div>
                    <h1 class="h3 fw-bold mb-1">Edit event</h1>
                    <p class="text-secondary mb-0">Keep the details current so students always see the latest info.</p>
                </div>
                <a href="{{ url('/events/' . $event->id) }}" class="btn btn-outline-secondary btn-sm">
                    <i class='bx bx-show align-middle'></i> View on site
                </a>
            </div>

            @include('organizer_dashboard.form', [
                'action' => url('/organizer/events/' . $event->id),
                'method' => 'PUT',
                'event' => $event,
                'categories' => $categories,
                'submitLabel' => 'Save changes',
            ])

        </div>
    </div>
</div>
@endsection
