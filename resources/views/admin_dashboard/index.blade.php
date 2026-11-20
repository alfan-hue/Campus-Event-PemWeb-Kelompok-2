@extends('layouts.app')

@section('content')
@php
    // Status yang memang digunakan pada kolom events.status.
    $statusMap = [
        'published' => ['Published', 'bg-success'],
        'pending' => ['Pending review', 'bg-warning text-dark'],
    ];

    $maxCount = max(1, (int) $categorySummary->max('events_count'));

    $statCards = [
        ['label' => 'Total events', 'value' => $stats['events'], 'icon' => 'bx-calendar-event'],
        ['label' => 'Pending review', 'value' => $stats['pending'], 'icon' => 'bx-time-five'],
        ['label' => 'Organizers', 'value' => $stats['organizers'], 'icon' => 'bx-group'],
        ['label' => 'Categories', 'value' => $stats['categories'], 'icon' => 'bx-category'],
    ];
@endphp

<div class="container py-4">
    <div class="row g-4">

        <div class="col-lg-3">
            @include('admin_dashboard.sidebar')
        </div>

        <div class="col-lg-9">

            {{-- Welcome banner --}}
            <div class="hero text-white rounded-4 shadow p-4 p-lg-5 mb-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div>
                        <h1 class="h3 fw-bold mb-1">Admin dashboard</h1>
                        <p class="mb-0" style="opacity: .9;">Monitor campus events, review submissions, and manage categories.</p>
                    </div>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-light text-primary fw-semibold">
                        <i class='bx bx-plus align-middle'></i> Add category
                    </a>
                </div>
            </div>

            {{-- Statistics --}}
            <div class="row g-3 mb-4">
                @foreach ($statCards as $card)
                    <div class="col-6 col-xl-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                <div class="icon-circle d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 text-primary">
                                    <i class='bx {{ $card['icon'] }} fs-4'></i>
                                </div>
                                <div>
                                    <div class="h4 fw-bold mb-0">{{ $card['value'] }}</div>
                                    <div class="small text-secondary">{{ $card['label'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="row g-4 mb-4">

                {{-- Recent events --}}
                <div class="col-xl-8">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h2 class="h5 fw-bold mb-0">Recent events</h2>
                                <span class="small text-secondary">Latest submissions</span>
                            </div>

                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">Event</th>
                                            <th scope="col">Category</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($recentEvents as $event)
                                            @php
                                                [$label, $class] = $statusMap[$event->status] ?? ['Unknown', 'bg-secondary'];
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="fw-semibold">{{ $event->title }}</div>
                                                    <div class="small text-secondary">{{ $event->organizer?->name ?? 'Unknown organizer' }}</div>
                                                </td>
                                                <td>
                                                    <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary">
                                                        {{ $event->category?->name ?? 'Uncategorized' }}
                                                    </span>
                                                </td>
                                                <td class="text-nowrap">
                                                    {{ $event->date?->format('d M Y') ?? '—' }}
                                                </td>
                                                <td><span class="badge {{ $class }}">{{ $label }}</span></td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-secondary py-4">
                                                    No events have been submitted yet.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Events per category --}}
                <div class="col-xl-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h2 class="h5 fw-bold mb-0">Events by category</h2>
                                <a href="{{ route('admin.categories.index') }}" class="small fw-semibold text-decoration-none">Manage</a>
                            </div>

                            @forelse ($categorySummary as $item)
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span class="fw-semibold">{{ $item->name }}</span>
                                        <span class="text-secondary">{{ $item->events_count }}</span>
                                    </div>
                                    <div class="progress" style="height: .5rem;" role="progressbar"
                                         aria-label="{{ $item->name }}"
                                         aria-valuenow="{{ $item->events_count }}"
                                         aria-valuemin="0"
                                         aria-valuemax="{{ $maxCount }}">
                                        <div class="progress-bar" style="width: {{ ($item->events_count / $maxCount) * 100 }}%"></div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-secondary mb-0">No categories yet. Add one so organizers can classify their events.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent organizers --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="h5 fw-bold mb-0">Recent organizers</h2>
                        <span class="small text-secondary">Non-admin users</span>
                    </div>

                    <div class="list-group list-group-flush">
                        @forelse ($recentOrganizers as $organizer)
                            @php
                                $initials = collect(preg_split('/\s+/', trim($organizer->name)))
                                    ->filter()
                                    ->take(2)
                                    ->map(fn ($word) => mb_strtoupper(mb_substr($word, 0, 1)))
                                    ->implode('');
                            @endphp
                            <div class="list-group-item px-0 d-flex align-items-center gap-3">
                                <div class="icon-circle d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 text-primary fw-bold">
                                    {{ $initials }}
                                </div>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="fw-semibold text-truncate">{{ $organizer->name }}</div>
                                    <div class="small text-secondary text-truncate">{{ $organizer->email }}</div>
                                </div>
                                <span class="badge rounded-pill bg-light text-dark border">
                                    {{ $organizer->events_count }} {{ \Illuminate\Support\Str::plural('event', $organizer->events_count) }}
                                </span>
                            </div>
                        @empty
                            <p class="text-secondary mb-0">No organizers have registered yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
