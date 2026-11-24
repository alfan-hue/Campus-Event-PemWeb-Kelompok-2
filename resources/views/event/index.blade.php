@extends('layouts.app')

@section('content')
@php
    $categories = $categories ?? [];
    $events = $events ?? [];

    $isPaginated = $events instanceof \Illuminate\Pagination\LengthAwarePaginator;
    $total = $isPaginated ? $events->total() : count($events);
    $hasFilters = request('q') || request('category') || request('status');
@endphp

<style>
    .hero { background: linear-gradient(135deg, #0d6efd 0%, #0a4fc4 100%); }
</style>

{{-- Header + filters --}}
<header class="hero text-white py-5">
    <div class="container py-lg-3">
        <div class="text-center mb-4">
            <h1 class="display-6 fw-bold mb-2">Browse events</h1>
            <p class="lead mb-0 mx-auto" style="max-width: 40rem; opacity: .9;">
                Search every ongoing, upcoming, and past event on campus.
            </p>
        </div>

        <form action="{{ url('/events') }}" method="GET" class="bg-white rounded-3 shadow p-3 mx-auto" style="max-width: 60rem;">
            <div class="row g-2">
                <div class="col-md-4">
                    <label for="q" class="visually-hidden">Search events</label>
                    <input type="search" id="q" name="q" value="{{ request('q') }}" class="form-control form-control-lg" placeholder="Search by name or keyword">
                </div>
                <div class="col-md-3">
                    <label for="category" class="visually-hidden">Category</label>
                    <select id="category" name="category" class="form-select form-select-lg">
                        <option value="">All categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="visually-hidden">Status</label>
                    <select id="status" name="status" class="form-select form-select-lg">
                        <option value="">Any time</option>
                        <option value="ongoing" @selected(request('status') === 'ongoing')>Ongoing</option>
                        <option value="upcoming" @selected(request('status') === 'upcoming')>Upcoming</option>
                        <option value="past" @selected(request('status') === 'past')>Ended</option>
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">Search</button>
                </div>
            </div>
        </form>
    </div>
</header>

<main class="container py-5">

    {{-- Result summary --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <p class="mb-0 text-secondary">
            Showing <strong class="text-dark">{{ $total }}</strong> {{ \Illuminate\Support\Str::plural('event', $total) }}
            @if (request('q')) for “{{ request('q') }}” @endif
            @if (request('category')) in {{ request('category') }} @endif
        </p>

        @if ($hasFilters)
            <a href="{{ url('/events') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                <i class='bx bx-x align-middle'></i> Clear filters
            </a>
        @endif
    </div>

    {{-- Events grid --}}
    <div class="row g-4">
        @forelse ($events as $event)
            <div class="col-md-6 col-lg-4">
                @include('event.card', ['event' => $event])
            </div>
        @empty
            <div class="col-12">
                <div class="hero-light rounded-4 text-center p-5" style="background: #f5f9ff; border: 1px solid #cfe2ff;">
                    <i class='bx bx-search-alt fs-1 text-primary'></i>
                    <h2 class="h5 fw-bold mt-2">No events match your search</h2>
                    <p class="text-secondary mb-3">Try a different keyword, or clear the filters to see everything.</p>
                    <a href="{{ url('/events') }}" class="btn btn-primary rounded-pill px-4">Show all events</a>
                </div>
            </div>
        @endforelse
    </div>

    @if ($isPaginated)
        <div class="mt-5 d-flex justify-content-center">
            {{ $events->withQueryString()->links() }}
        </div>
    @endif
</main>
@endsection
