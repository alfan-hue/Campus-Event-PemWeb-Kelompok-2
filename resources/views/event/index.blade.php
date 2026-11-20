@extends('layouts.app')

@section('content')
@php
    // Sample data so the page renders on its own.
    // In your controller, pass $events (array, collection or paginator) and $categories, then delete this block.
    $categories = $categories ?? ['Seminar', 'Workshop', 'Competition', 'Sports', 'Arts & Culture', 'Community Service'];

    $sampleEvents = [
        ['id' => 1, 'title' => 'Student Innovation Expo', 'category' => 'Competition', 'status' => 'ongoing', 'date' => '18 – 22 Nov 2026', 'time' => '09:00 – 17:00', 'location' => 'Main Hall, Building A', 'organizer' => 'Faculty of Engineering', 'description' => 'Showcase of student projects from every department, with live demos and judging.', 'registration_url' => 'https://example.com/register/innovation-expo'],
        ['id' => 2, 'title' => 'Campus Art Week', 'category' => 'Arts & Culture', 'status' => 'ongoing', 'date' => '16 – 22 Nov 2026', 'time' => '10:00 – 20:00', 'location' => 'Central Courtyard', 'organizer' => 'Arts Student Union', 'description' => 'Exhibitions, live performances, and open studios by student artists.', 'registration_url' => 'https://example.com/register/art-week'],
        ['id' => 3, 'title' => 'Career Readiness Seminar', 'category' => 'Seminar', 'status' => 'upcoming', 'date' => '28 Nov 2026', 'time' => '13:00 – 15:30', 'location' => 'Auditorium', 'organizer' => 'Career Center', 'description' => 'Learn CV writing, interview skills, and how to approach employers.', 'registration_url' => 'https://example.com/register/career-seminar'],
        ['id' => 4, 'title' => 'Intro to Web Development Workshop', 'category' => 'Workshop', 'status' => 'upcoming', 'date' => '03 Dec 2026', 'time' => '09:00 – 12:00', 'location' => 'Computer Lab 2', 'organizer' => 'Informatics Student Association', 'description' => 'Hands-on beginner workshop covering HTML, CSS, and your first Laravel page.', 'registration_url' => 'https://example.com/register/web-workshop'],
        ['id' => 5, 'title' => 'Inter-Faculty Futsal Cup', 'category' => 'Sports', 'status' => 'upcoming', 'date' => '10 Dec 2026', 'time' => '08:00 – 16:00', 'location' => 'Sports Hall', 'organizer' => 'Sports Committee', 'description' => 'Annual futsal tournament. Teams of 7, registration closes one week before.', 'registration_url' => 'https://example.com/register/futsal-cup'],
        ['id' => 6, 'title' => 'Beach Clean-Up Day', 'category' => 'Community Service', 'status' => 'upcoming', 'date' => '13 Dec 2026', 'time' => '06:30 – 11:00', 'location' => 'Meeting point: Campus Gate', 'organizer' => 'Volunteer Club', 'description' => 'Join fellow students for a morning of community service. Transport provided.', 'registration_url' => 'https://example.com/register/clean-up'],
        ['id' => 7, 'title' => 'New Student Orientation', 'category' => 'Seminar', 'status' => 'past', 'date' => '05 Nov 2026', 'time' => '08:00 – 14:00', 'location' => 'Auditorium', 'organizer' => 'Student Affairs', 'description' => 'A full-day welcome covering campus services, clubs, and academic tips for new students.', 'registration_url' => 'https://example.com/register/orientation'],
        ['id' => 8, 'title' => 'Open Mic Night', 'category' => 'Arts & Culture', 'status' => 'past', 'date' => '12 Nov 2026', 'time' => '19:00 – 22:00', 'location' => 'Student Cafe', 'organizer' => 'Music Club', 'description' => 'An evening of music, poetry, and stand-up from student performers.', 'registration_url' => 'https://example.com/register/open-mic'],
    ];

    // Filters the sample data by the search form so the page can be previewed.
    $events = $events ?? collect($sampleEvents)
        ->when(request('q'), fn ($c, $q) => $c->filter(fn ($e) => str_contains(mb_strtolower($e['title'] . ' ' . $e['description']), mb_strtolower($q))))
        ->when(request('category'), fn ($c, $cat) => $c->where('category', $cat))
        ->when(request('status'), fn ($c, $s) => $c->where('status', $s))
        ->values()
        ->all();

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
