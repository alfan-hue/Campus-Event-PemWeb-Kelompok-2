@extends('layouts.app')

@section('content')
@php
    // Sample data so the page renders on its own.
    // In your controller, pass $event (and optionally $relatedEvents), then delete this block.
    $event = $event ?? [
        'id' => 1,
        'title' => 'Student Innovation Expo',
        'category' => 'Competition',
        'status' => 'ongoing',
        'date' => '18 – 22 Nov 2026',
        'time' => '09:00 – 17:00',
        'location' => 'Main Hall, Building A',
        'organizer' => 'Faculty of Engineering',
        'image' => null,
        'registration_url' => 'https://example.com/register/innovation-expo',
        'description' => "Student Innovation Expo is a five-day showcase of projects built by students from every department.\n\nVisit the exhibition booths, watch live demos, and talk with the teams behind each project. A panel of lecturers and industry guests will judge entries in three tracks: technology, social impact, and design.\n\nEntry is free for all students. Bring your student ID, and register through the organizer's link to receive your visitor pass.",
    ];

    $relatedEvents = $relatedEvents ?? [
        ['id' => 4, 'title' => 'Intro to Web Development Workshop', 'category' => 'Workshop', 'status' => 'upcoming', 'date' => '03 Dec 2026', 'time' => '09:00 – 12:00', 'location' => 'Computer Lab 2', 'organizer' => 'Informatics Student Association', 'description' => 'Hands-on beginner workshop covering HTML, CSS, and your first Laravel page.', 'registration_url' => 'https://example.com/register/web-workshop'],
        ['id' => 5, 'title' => 'Inter-Faculty Futsal Cup', 'category' => 'Sports', 'status' => 'upcoming', 'date' => '10 Dec 2026', 'time' => '08:00 – 16:00', 'location' => 'Sports Hall', 'organizer' => 'Sports Committee', 'description' => 'Annual futsal tournament. Teams of 7, registration closes one week before.', 'registration_url' => 'https://example.com/register/futsal-cup'],
        ['id' => 6, 'title' => 'Beach Clean-Up Day', 'category' => 'Community Service', 'status' => 'upcoming', 'date' => '13 Dec 2026', 'time' => '06:30 – 11:00', 'location' => 'Meeting point: Campus Gate', 'organizer' => 'Volunteer Club', 'description' => 'Join fellow students for a morning of community service. Transport provided.', 'registration_url' => 'https://example.com/register/clean-up'],
    ];

    $statusMap = [
        'ongoing' => ['Ongoing', 'bg-success'],
        'upcoming' => ['Upcoming', 'bg-primary'],
        'past' => ['Ended', 'bg-secondary'],
    ];
    [$statusLabel, $statusClass] = $statusMap[$event['status'] ?? 'upcoming'] ?? $statusMap['upcoming'];
    $isPast = ($event['status'] ?? '') === 'past';
    $paragraphs = preg_split('/\R{2,}/', trim($event['description']));
@endphp

<div class="container py-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-3">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/events') }}" class="text-decoration-none">Events</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $event['title'] }}</li>
        </ol>
    </nav>

    <div class="row g-4 g-lg-5">

        {{-- Main content --}}
        <div class="col-lg-8">
            <img
                src="{{ $event['image'] ?? 'https://placehold.co/600x400' }}"
                alt="{{ $event['title'] }}"
                class="w-100 rounded-4 shadow-sm mb-4"
                style="aspect-ratio: 16 / 9; object-fit: cover;"
            >

            <div class="d-flex flex-wrap gap-2 mb-3">
                <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary fs-6 fw-semibold">{{ $event['category'] }}</span>
                <span class="badge rounded-pill {{ $statusClass }} fs-6 fw-semibold">{{ $statusLabel }}</span>
            </div>

            <h1 class="display-6 fw-bold mb-4">{{ $event['title'] }}</h1>

            <h2 class="h5 fw-bold mb-3">About this event</h2>
            <div class="text-secondary" style="max-width: 44rem; line-height: 1.75;">
                @foreach ($paragraphs as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm position-sticky" style="top: 5.5rem;">
                <div class="card-body p-4">
                    <h2 class="h5 fw-bold mb-4">Event details</h2>

                    <ul class="list-unstyled d-grid gap-3 mb-4">
                        <li class="d-flex gap-3">
                            <i class='bx bx-calendar fs-4 text-primary'></i>
                            <div>
                                <div class="small text-secondary">Date</div>
                                <div class="fw-semibold">{{ $event['date'] }}</div>
                            </div>
                        </li>
                        <li class="d-flex gap-3">
                            <i class='bx bx-time-five fs-4 text-primary'></i>
                            <div>
                                <div class="small text-secondary">Time</div>
                                <div class="fw-semibold">{{ $event['time'] }}</div>
                            </div>
                        </li>
                        <li class="d-flex gap-3">
                            <i class='bx bx-map fs-4 text-primary'></i>
                            <div>
                                <div class="small text-secondary">Location</div>
                                <div class="fw-semibold">{{ $event['location'] }}</div>
                            </div>
                        </li>
                        <li class="d-flex gap-3">
                            <i class='bx bx-buildings fs-4 text-primary'></i>
                            <div>
                                <div class="small text-secondary">Organizer</div>
                                <div class="fw-semibold">{{ $event['organizer'] }}</div>
                            </div>
                        </li>
                    </ul>

                    @if ($isPast)
                        <div class="alert alert-secondary mb-3" role="status">This event has ended.</div>
                    @else
                        <a href="{{ $event['registration_url'] }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-lg w-100 mb-2">
                            Register for this event
                        </a>
                        <p class="small text-secondary mb-3">Registration is handled by the organizer, so you will leave Campus Event.</p>
                    @endif

                    <button type="button" id="copyLink" class="btn btn-outline-primary w-100" data-url="{{ url('/events/' . $event['id']) }}">
                        <i class='bx bx-link align-middle'></i> <span>Copy event link</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Related events --}}
    @if (count($relatedEvents))
        <section class="mt-5 pt-3" aria-label="More events">
            <div class="d-flex justify-content-between align-items-end mb-3">
                <h2 class="h3 fw-bold mb-0">More events you might like</h2>
                <a href="{{ url('/events') }}" class="text-decoration-none fw-semibold">See all events</a>
            </div>
            <div class="row g-4">
                @foreach ($relatedEvents as $related)
                    <div class="col-md-6 col-lg-4">
                        @include('event.card', ['event' => $related])
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>

<script>
    // Wait for the page to load so jQuery (from layouts/app.blade.php) is ready.
    window.addEventListener('load', function () {
        (function ($) {
            if (!$) { return; }

            $('#copyLink').on('click', function () {
                var $btn = $(this);
                var $label = $btn.find('span');
                var url = $btn.data('url');

                function done() {
                    $label.text('Link copied');
                    setTimeout(function () { $label.text('Copy event link'); }, 2000);
                }

                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(url).then(done);
                } else {
                    window.prompt('Copy this link:', url);
                }
            });
        })(window.jQuery);
    });
</script>
@endsection
