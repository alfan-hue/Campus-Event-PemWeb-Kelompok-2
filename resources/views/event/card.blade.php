{{--
    Event card partial.
    Usage: <div class="col-md-6 col-lg-4">@include('event.card', ['event' => $event])</div>
    Expects: id, title, category, status (ongoing|upcoming|past), date, time, location,
             organizer, description, registration_url, image (optional)
--}}
@php
    $statusMap = [
        'ongoing' => ['Ongoing', 'bg-success'],
        'upcoming' => ['Upcoming', 'bg-primary'],
        'past' => ['Ended', 'bg-secondary'],
    ];
    [$statusLabel, $statusClass] = $statusMap[$event['status'] ?? 'upcoming'] ?? $statusMap['upcoming'];
    $isPast = ($event['status'] ?? '') === 'past';
@endphp

@once
    <style>
        .event-card { transition: transform .15s ease, box-shadow .15s ease; }
        .event-card:hover { transform: translateY(-3px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.12) !important; }
        .event-image { aspect-ratio: 3 / 2; object-fit: cover; width: 100%; }
        .meta-row { display: flex; align-items: flex-start; gap: .5rem; }
        .meta-row i { flex-shrink: 0; margin-top: .1rem; font-size: 1rem; }
        @media (prefers-reduced-motion: reduce) {
            .event-card { transition: none; }
            .event-card:hover { transform: none; }
        }
    </style>
@endonce

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
            <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
        </div>

        <h3 class="h5 fw-semibold">{{ $event['title'] }}</h3>
        <p class="text-secondary small">{{ \Illuminate\Support\Str::limit($event['description'], 110) }}</p>

        <div class="small text-secondary mt-auto mb-3 d-grid gap-1">
            <div class="meta-row"><i class='bx bx-calendar'></i><span>{{ $event['date'] }}</span></div>
            <div class="meta-row"><i class='bx bx-time-five'></i><span>{{ $event['time'] }}</span></div>
            <div class="meta-row"><i class='bx bx-map'></i><span>{{ $event['location'] }}</span></div>
            <div class="meta-row"><i class='bx bx-buildings'></i><span>{{ $event['organizer'] }}</span></div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ url('/events/' . $event['id']) }}" class="btn btn-outline-primary btn-sm flex-fill">View details</a>
            @if ($isPast)
                <span class="btn btn-outline-secondary btn-sm flex-fill disabled">Ended</span>
            @elseif (!empty($event['registration_url']))
                <a href="{{ $event['registration_url'] }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm flex-fill">Register</a>
            @else
                <span class="btn btn-outline-secondary btn-sm flex-fill disabled">No link</span>
            @endif
        </div>
    </div>
</article>
