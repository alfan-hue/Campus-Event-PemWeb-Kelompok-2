@php
    $categories = $categories ?? ['Seminar', 'Workshop', 'Competition', 'Sports', 'Arts & Culture', 'Community Service'];

    $ongoingEvents = $ongoingEvents ?? [
        [
            'id' => 1,
            'title' => 'Student Innovation Expo',
            'category' => 'Competition',
            'date' => '10 – 12 Oct 2026',
            'time' => '09:00 – 17:00',
            'location' => 'Main Hall, Building A',
            'organizer' => 'Faculty of Engineering',
            'description' => 'Showcase of student projects from every department, with live demos and judging.',
            'registration_url' => 'https://example.com/register/innovation-expo',
        ],
        [
            'id' => 2,
            'title' => 'Campus Art Week',
            'category' => 'Arts & Culture',
            'date' => '08 – 14 Oct 2026',
            'time' => '10:00 – 20:00',
            'location' => 'Central Courtyard',
            'organizer' => 'Arts Student Union',
            'description' => 'Exhibitions, live performances, and open studios by student artists.',
            'registration_url' => 'https://example.com/register/art-week',
        ],
    ];

    $upcomingEvents = $upcomingEvents ?? [
        [
            'id' => 3,
            'title' => 'Career Readiness Seminar',
            'category' => 'Seminar',
            'date' => '18 Oct 2026',
            'time' => '13:00 – 15:30',
            'location' => 'Auditorium',
            'organizer' => 'Career Center',
            'description' => 'Learn CV writing, interview skills, and how to approach employers.',
            'registration_url' => 'https://example.com/register/career-seminar',
        ],
        [
            'id' => 4,
            'title' => 'Intro to Web Development Workshop',
            'category' => 'Workshop',
            'date' => '22 Oct 2026',
            'time' => '09:00 – 12:00',
            'location' => 'Computer Lab 2',
            'organizer' => 'Informatics Student Association',
            'description' => 'Hands-on beginner workshop covering HTML, CSS, and your first Laravel page.',
            'registration_url' => 'https://example.com/register/web-workshop',
        ],
        [
            'id' => 5,
            'title' => 'Inter-Faculty Futsal Cup',
            'category' => 'Sports',
            'date' => '29 Oct 2026',
            'time' => '08:00 – 16:00',
            'location' => 'Sports Hall',
            'organizer' => 'Sports Committee',
            'description' => 'Annual futsal tournament. Teams of 7, registration closes one week before.',
            'registration_url' => 'https://example.com/register/futsal-cup',
        ],
        [
            'id' => 6,
            'title' => 'Beach Clean-Up Day',
            'category' => 'Community Service',
            'date' => '01 Nov 2026',
            'time' => '06:30 – 11:00',
            'location' => 'Meeting point: Campus Gate',
            'organizer' => 'Volunteer Club',
            'description' => 'Join fellow students for a morning of community service. Transport provided.',
            'registration_url' => 'https://example.com/register/clean-up',
        ],
    ];

    $sections = [
        ['title' => 'Happening Now', 'subtitle' => 'Ongoing events you can join today.', 'badge' => 'Ongoing', 'badgeClass' => 'bg-success', 'events' => $ongoingEvents],
        ['title' => 'Upcoming Events', 'subtitle' => 'Mark your calendar for what is coming next.', 'badge' => 'Upcoming', 'badgeClass' => 'bg-primary', 'events' => $upcomingEvents],
    ];
@endphp


@extends('layouts.app')

@section('content')

    <header class="hero text-white py-5">
        <div class="container py-lg-4">
            <div class="text-center mb-4">
                <h1 class="display-5 fw-bold mb-3">Every campus event, in one place</h1>
                <p class="lead mb-0 mx-auto" style="max-width: 44rem; opacity: .9;">
                    See what is happening now, find what is coming up, read the details, and register through the organizer's link.
                </p>
            </div>

            <form action="{{ url('/') }}" method="GET" class="bg-white rounded-3 shadow p-3 mx-auto" style="max-width: 52rem;">
                <div class="row g-2">
                    <div class="col-md-6">
                        <label for="q" class="visually-hidden">Search events</label>
                        <input type="search" id="q" name="q" value="{{ request('q') }}" class="form-control form-control-lg" placeholder="Search events by name or keyword">
                    </div>
                    <div class="col-md-4">
                        <label for="category" class="visually-hidden">Category</label>
                        <select id="category" name="category" class="form-select form-select-lg">
                            <option value="">All categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
                            @endforeach
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

        {{-- Category quick filters --}}
        <section class="mb-5" aria-label="Browse by category">
            <h2 class="h5 fw-semibold mb-3">Browse by category</h2>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ url('/') }}" class="btn btn-sm rounded-pill {{ request('category') ? 'btn-outline-primary' : 'btn-primary' }}">All</a>
                @foreach ($categories as $category)
                    <a href="{{ url('/') }}?category={{ urlencode($category) }}"
                    class="btn btn-sm rounded-pill {{ request('category') === $category ? 'btn-primary' : 'btn-outline-primary' }}">
                        {{ $category }}
                    </a>
                @endforeach
            </div>
        </section>

        {{-- Ongoing + Upcoming sections --}}
        @foreach ($sections as $section)
            <section class="mb-5" aria-label="{{ $section['title'] }}">
                <div class="mb-3">
                    <h2 class="h3 fw-bold mb-1">{{ $section['title'] }}</h2>
                    <p class="text-secondary mb-0">{{ $section['subtitle'] }}</p>
                </div>

                <div class="row g-4">
                    @forelse ($section['events'] as $event)
                        <div class="col-md-6 col-lg-4">
                            <article class="card event-card h-100 border-0 shadow-sm overflow-hidden">
                                <img
                                    src="{{ $event['image'] ?? 'https://placehold.co/600x400' }}"
                                    class="card-img-top event-image"
                                    alt="{{ $event['title'] }}"
                                    loading="lazy"
                                >
                                <div class="card-body p-4 d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary">{{ $event['category'] }}</span>
                                        <span class="badge {{ $section['badgeClass'] }}">{{ $section['badge'] }}</span>
                                    </div>

                                    <h3 class="h5 fw-semibold">{{ $event['title'] }}</h3>
                                    <p class="text-secondary small">{{ \Illuminate\Support\Str::limit($event['description'], 110) }}</p>

                                    <div class="small text-secondary mt-auto mb-3 d-grid gap-1">
                                        <div class="meta-row">
                                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <span>{{ $event['date'] }}</span>
                                        </div>
                                        <div class="meta-row">
                                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span>{{ $event['time'] }}</span>
                                        </div>
                                        <div class="meta-row">
                                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            <span>{{ $event['location'] }}</span>
                                        </div>
                                        <div class="meta-row">
                                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            <span>{{ $event['organizer'] }}</span>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <a href="{{ url('/events/' . $event['id']) }}" class="btn btn-outline-primary btn-sm flex-fill">View details</a>
                                        <a href="{{ $event['registration_url'] }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm flex-fill">Register</a>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-light border text-center mb-0">No events found in this section.</div>
                        </div>
                    @endforelse
                </div>
            </section>
        @endforeach

        @include('layouts.how-it-works')

        {{-- For organizers --}}

        @include('organizers.card')

        <style>
            .hero {
                background: linear-gradient(135deg, #0d6efd 0%, #0a4fc4 100%);
            }

            .hero-light {
                background: linear-gradient(135deg, #e7f1ff 0%, #f5f9ff 100%);
                border: 1px solid #cfe2ff;
            }

            .icon-circle { width: 3rem; height: 3rem; flex-shrink: 0; }
            .event-card { transition: transform .15s ease, box-shadow .15s ease; }
            .event-card:hover { transform: translateY(-3px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.12) !important; }
            .event-image { aspect-ratio: 3 / 2; object-fit: cover; width: 100%; }
            .meta-row { display: flex; align-items: flex-start; gap: .5rem; }
            .meta-row svg { flex-shrink: 0; margin-top: .15rem; }
        </style>

@endsection
