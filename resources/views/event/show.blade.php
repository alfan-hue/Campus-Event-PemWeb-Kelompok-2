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

    // Review defaults (the controller normally passes these)
    $reviews = $reviews ?? collect();
    $reviewStats = $reviewStats ?? ['total' => 0, 'positive' => 0, 'negative' => 0];
    $myReview = $myReview ?? null;
    $canReview = $canReview ?? true;
    $reviewFilter = $reviewFilter ?? request('filter');

    $statusMap = [
        'ongoing' => ['Ongoing', 'bg-success'],
        'upcoming' => ['Upcoming', 'bg-primary'],
        'past' => ['Ended', 'bg-secondary'],
    ];
    [$statusLabel, $statusClass] = $statusMap[$event['status'] ?? 'upcoming'] ?? $statusMap['upcoming'];
    $isPast = ($event['status'] ?? '') === 'past';
    $paragraphs = preg_split('/\R{2,}/', trim($event['description']));

    // Review summary (Steam-style)
    $ratedCount = $reviewStats['positive'] + $reviewStats['negative'];
    $neutralCount = $reviewStats['total'] - $ratedCount;
    $positivePct = $ratedCount > 0 ? (int) round($reviewStats['positive'] / $ratedCount * 100) : null;
    $negativePct = $positivePct !== null ? 100 - $positivePct : null;

    if ($ratedCount < 3) {
        [$summaryLabel, $summaryClass] = ['Not enough ratings yet', 'text-secondary'];
    } elseif ($positivePct >= 80) {
        [$summaryLabel, $summaryClass] = ['Very Positive', 'text-success'];
    } elseif ($positivePct >= 70) {
        [$summaryLabel, $summaryClass] = ['Mostly Positive', 'text-success'];
    } elseif ($positivePct >= 40) {
        [$summaryLabel, $summaryClass] = ['Mixed', 'text-warning'];
    } elseif ($positivePct >= 20) {
        [$summaryLabel, $summaryClass] = ['Mostly Negative', 'text-danger'];
    } else {
        [$summaryLabel, $summaryClass] = ['Very Negative', 'text-danger'];
    }

    $reviewTabs = [
        null => 'All',
        'positive' => 'Recommended',
        'negative' => 'Not recommended',
        'neutral' => 'Comment only',
    ];

    // Selected rating in the form: '1', '0' or '' (comment only)
    $selectedRating = (string) old('rating', $myReview->rating ?? '');
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

            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary fs-6 fw-semibold">{{ $event['category'] }}</span>
                <span class="badge rounded-pill {{ $statusClass }} fs-6 fw-semibold">{{ $statusLabel }}</span>
                <a href="#reviews" class="text-decoration-none small ms-1 {{ $summaryClass }} fw-semibold">
                    @if ($positivePct !== null && $ratedCount >= 3)
                        {{ $summaryLabel }} ({{ $positivePct }}%)
                    @else
                        {{ $reviewStats['total'] }} {{ \Illuminate\Support\Str::plural('review', $reviewStats['total']) }}
                    @endif
                </a>
            </div>

            <h1 class="display-6 fw-bold mb-4">{{ $event['title'] }}</h1>

            <h2 class="h5 fw-bold mb-3">About this event</h2>
            <div class="text-secondary" style="max-width: 44rem; line-height: 1.75;">
                @foreach ($paragraphs as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            </div>

            {{-- Reviews --}}
            <section id="reviews" class="mt-5 pt-3" aria-label="Reviews" style="scroll-margin-top: 5.5rem;">
                <h2 class="h4 fw-bold mb-3">Reviews</h2>

                @if (session('status'))
                    <div class="alert alert-success" role="status">{{ session('status') }}</div>
                @endif

                {{-- Summary --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-3">
                            <div>
                                <div class="small text-secondary">Overall reviews</div>
                                <div class="h4 fw-bold mb-0 {{ $summaryClass }}">{{ $summaryLabel }}</div>
                            </div>
                            <div class="small text-secondary text-md-end">
                                {{ $reviewStats['total'] }} {{ \Illuminate\Support\Str::plural('review', $reviewStats['total']) }}
                                @if ($positivePct !== null)
                                    &middot; {{ $positivePct }}% of {{ $ratedCount }} {{ \Illuminate\Support\Str::plural('rating', $ratedCount) }} are positive
                                @endif
                            </div>
                        </div>

                        @if ($ratedCount > 0)
                            <div class="progress" style="height: .75rem;" role="img" aria-label="{{ $positivePct }}% positive, {{ $negativePct }}% negative">
                                <div class="progress-bar bg-success" style="width: {{ $positivePct }}%"></div>
                                <div class="progress-bar bg-danger" style="width: {{ $negativePct }}%"></div>
                            </div>
                        @else
                            <div class="progress" style="height: .75rem;"><div class="progress-bar bg-secondary" style="width: 100%; opacity: .25;"></div></div>
                        @endif

                        <div class="d-flex flex-wrap gap-4 small mt-3">
                            <span class="text-success"><i class='bx bxs-like align-middle'></i> {{ $reviewStats['positive'] }} Recommended</span>
                            <span class="text-danger"><i class='bx bxs-dislike align-middle'></i> {{ $reviewStats['negative'] }} Not recommended</span>
                            <span class="text-secondary"><i class='bx bx-message-square-detail align-middle'></i> {{ $neutralCount }} Comment only</span>
                        </div>
                    </div>
                </div>

                {{-- Write / edit review --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        @guest
                            <h3 class="h6 fw-bold mb-2">Share your experience</h3>
                            <p class="text-secondary mb-3">Log in to write a review for this event.</p>
                            <a href="{{ url('/login') }}" class="btn btn-primary">Log in to review</a>
                        @else
                            @if (! $canReview && ! $myReview)
                                <h3 class="h6 fw-bold mb-2">Reviews open soon</h3>
                                <p class="text-secondary mb-0">You can write a review once this event has started.</p>
                            @else
                                <h3 class="h6 fw-bold mb-3">{{ $myReview ? 'Your review' : 'Write a review' }}</h3>

                                <form method="POST" action="{{ route('reviews.store', $event['id']) }}">
                                    @csrf

                                    <div class="mb-3">
                                        <div class="small text-secondary mb-2">Do you recommend this event?</div>
                                        <div class="d-flex flex-wrap gap-2">
                                            <input type="radio" class="btn-check" name="rating" id="ratingLike" value="L" {{ $selectedRating === 'L' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-success" for="ratingLike"><i class='bx bx-like align-middle'></i> Recommended</label>

                                            <input type="radio" class="btn-check" name="rating" id="ratingDislike" value="D" {{ $selectedRating === 'D' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-danger" for="ratingDislike"><i class='bx bx-dislike align-middle'></i> Not recommended</label>

                                            <input type="radio" class="btn-check" name="rating" id="ratingNeutral" value="" {{ $selectedRating === '' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-secondary" for="ratingNeutral"><i class='bx bx-message-square-detail align-middle'></i> Comment only</label>
                                        </div>
                                        @error('rating')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="reviewContent" class="form-label small text-secondary">Your comment</label>
                                        <textarea
                                            id="reviewContent"
                                            name="content"
                                            rows="4"
                                            maxlength="2000"
                                            class="form-control @error('content') is-invalid @enderror"
                                            placeholder="What did you think about this event?"
                                            required
                                        >{{ old('content', $myReview->content ?? '') }}</textarea>
                                        @error('content')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary">{{ $myReview ? 'Update review' : 'Post review' }}</button>
                                </form>

                                @if ($myReview)
                                    <form method="POST" action="{{ route('reviews.destroy', [$event['id'], $myReview->id]) }}" class="mt-2" onsubmit="return confirm('Delete your review?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger text-decoration-none p-0 small">Delete my review</button>
                                    </form>
                                @endif
                            @endif
                        @endguest
                    </div>
                </div>

                {{-- Filter --}}
                <ul class="nav nav-pills gap-1 mb-3">
                    @foreach ($reviewTabs as $key => $label)
                        <li class="nav-item">
                            <a
                                class="nav-link py-1 px-3 {{ (string) $reviewFilter === (string) $key ? 'active' : '' }}"
                                href="{{ url('/events/' . $event['id']) . ($key ? '?filter=' . $key : '') }}#reviews"
                            >{{ $label }}</a>
                        </li>
                    @endforeach
                </ul>

                {{-- Review list --}}
                @forelse ($reviews as $review)
                    <article class="card border-0 shadow-sm mb-3">
                        <div class="card-body p-4">
                            <div class="d-flex gap-3">
                                <div
                                    class="rounded-circle bg-primary bg-opacity-10 text-primary fw-bold d-flex align-items-center justify-content-center flex-shrink-0"
                                    style="width: 2.75rem; height: 2.75rem;"
                                    aria-hidden="true"
                                >{{ strtoupper(mb_substr($review->user->name ?? '?', 0, 1)) }}</div>

                                <div class="flex-grow-1">
                                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-1">
                                        <div class="fw-semibold">
                                            {{ $review->user->name ?? 'Anonymous' }}
                                            @auth
                                                @if (auth()->id() === $review->user_id)
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary ms-1">You</span>
                                                @endif
                                            @endauth
                                        </div>
                                        <div class="small text-secondary">{{ $review->created_at->diffForHumans() }}</div>
                                    </div>

                                    @if ($review->rating === 'L')
                                        <div class="text-success fw-semibold small mb-2"><i class='bx bxs-like align-middle fs-5'></i> Recommended</div>
                                    @elseif ($review->rating === 'D')
                                        <div class="text-danger fw-semibold small mb-2"><i class='bx bxs-dislike align-middle fs-5'></i> Not recommended</div>
                                    @else
                                        <div class="text-secondary fw-semibold small mb-2"><i class='bx bx-message-square-detail align-middle fs-5'></i> Comment</div>
                                    @endif

                                    <p class="mb-0" style="line-height: 1.7;">{!! nl2br(e($review->content)) !!}</p>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="text-center text-secondary py-5">
                        <i class='bx bx-message-square-detail fs-1 d-block mb-2'></i>
                        No reviews here yet. Be the first to share your thoughts!
                    </div>
                @endforelse

                @if (method_exists($reviews, 'links'))
                    <div class="mt-3">{{ $reviews->fragment('reviews')->links() }}</div>
                @endif
            </section>
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