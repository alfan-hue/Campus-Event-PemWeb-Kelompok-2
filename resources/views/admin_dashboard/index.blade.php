@extends('layouts.app')

@section('content')
@php
    // Sample data so the page renders on its own.
    // In your controller, pass these variables, then delete this block.
    $stats = $stats ?? ['events' => 48, 'ongoing' => 5, 'organizers' => 17, 'categories' => 6];

    $recentEvents = $recentEvents ?? [
        ['id' => 1, 'title' => 'Student Innovation Expo', 'organizer' => 'Faculty of Engineering', 'category' => 'Competition', 'status' => 'ongoing', 'date' => '18 Nov 2026'],
        ['id' => 3, 'title' => 'Career Readiness Seminar', 'organizer' => 'Career Center', 'category' => 'Seminar', 'status' => 'upcoming', 'date' => '28 Nov 2026'],
        ['id' => 4, 'title' => 'Intro to Web Development Workshop', 'organizer' => 'Informatics Student Association', 'category' => 'Workshop', 'status' => 'upcoming', 'date' => '03 Dec 2026'],
        ['id' => 7, 'title' => 'New Student Orientation', 'organizer' => 'Student Affairs', 'category' => 'Seminar', 'status' => 'past', 'date' => '05 Nov 2026'],
        ['id' => 8, 'title' => 'Open Mic Night', 'organizer' => 'Music Club', 'category' => 'Arts & Culture', 'status' => 'past', 'date' => '12 Nov 2026'],
    ];

    $categorySummary = $categorySummary ?? [
        ['name' => 'Seminar', 'count' => 14],
        ['name' => 'Workshop', 'count' => 11],
        ['name' => 'Competition', 'count' => 8],
        ['name' => 'Arts & Culture', 'count' => 7],
        ['name' => 'Sports', 'count' => 5],
        ['name' => 'Community Service', 'count' => 3],
    ];

    $recentOrganizers = $recentOrganizers ?? [
        ['name' => 'Informatics Student Association', 'email' => 'hmif@campus.ac.id', 'events' => 6],
        ['name' => 'Volunteer Club', 'email' => 'volunteer@campus.ac.id', 'events' => 3],
        ['name' => 'Music Club', 'email' => 'music@campus.ac.id', 'events' => 2],
    ];

    $statusMap = [
        'ongoing' => ['Ongoing', 'bg-success'],
        'upcoming' => ['Upcoming', 'bg-primary'],
        'past' => ['Ended', 'bg-secondary'],
    ];

    $maxCount = max(1, collect($categorySummary)->max('count'));

    $statCards = [
        ['label' => 'Total events', 'value' => $stats['events'], 'icon' => 'bx-calendar-event'],
        ['label' => 'Happening now', 'value' => $stats['ongoing'], 'icon' => 'bx-pulse'],
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
                        <p class="mb-0" style="opacity: .9;">Keep categories tidy and see what organizers are publishing.</p>
                    </div>
                    <a href="{{ url('/admin/categories/create') }}" class="btn btn-light text-primary fw-semibold">
                        <i class='bx bx-plus align-middle'></i> Add category
                    </a>
                </div>
            </div>

            {{-- Stats --}}
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
                                <a href="{{ url('/admin/events') }}" class="small fw-semibold text-decoration-none">View all</a>
                            </div>

                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">Event</th>
                                            <th scope="col">Category</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Status</th>
                                            <th scope="col" class="text-end">View</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($recentEvents as $event)
                                            @php [$label, $class] = $statusMap[$event['status']] ?? $statusMap['upcoming']; @endphp
                                            <tr>
                                                <td>
                                                    <div class="fw-semibold">{{ $event['title'] }}</div>
                                                    <div class="small text-secondary">{{ $event['organizer'] }}</div>
                                                </td>
                                                <td><span class="badge rounded-pill bg-primary bg-opacity-10 text-primary">{{ $event['category'] }}</span></td>
                                                <td class="text-nowrap">{{ $event['date'] }}</td>
                                                <td><span class="badge {{ $class }}">{{ $label }}</span></td>
                                                <td class="text-end">
                                                    <a href="{{ url('/events/' . $event['id']) }}" class="btn btn-sm btn-outline-primary" aria-label="View {{ $event['title'] }}">
                                                        <i class='bx bx-show'></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-secondary py-4">No events have been published yet.</td>
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
                                <a href="{{ url('/admin/categories') }}" class="small fw-semibold text-decoration-none">Manage</a>
                            </div>

                            @forelse ($categorySummary as $item)
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span class="fw-semibold">{{ $item['name'] }}</span>
                                        <span class="text-secondary">{{ $item['count'] }}</span>
                                    </div>
                                    <div class="progress" style="height: .5rem;" role="progressbar" aria-label="{{ $item['name'] }}" aria-valuenow="{{ $item['count'] }}" aria-valuemin="0" aria-valuemax="{{ $maxCount }}">
                                        <div class="progress-bar" style="width: {{ ($item['count'] / $maxCount) * 100 }}%"></div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-secondary mb-0">No categories yet. Add one so organizers can tag their events.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent organizers --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="h5 fw-bold mb-0">New organizers</h2>
                        <a href="{{ url('/admin/organizers') }}" class="small fw-semibold text-decoration-none">View all</a>
                    </div>

                    <div class="list-group list-group-flush">
                        @forelse ($recentOrganizers as $organizer)
                            @php
                                $initials = collect(preg_split('/\s+/', trim($organizer['name'])))
                                    ->filter()->take(2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
                            @endphp
                            <div class="list-group-item px-0 d-flex align-items-center gap-3">
                                <div class="icon-circle d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 text-primary fw-bold">{{ $initials }}</div>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="fw-semibold text-truncate">{{ $organizer['name'] }}</div>
                                    <div class="small text-secondary text-truncate">{{ $organizer['email'] }}</div>
                                </div>
                                <span class="badge rounded-pill bg-light text-dark border">{{ $organizer['events'] }} {{ \Illuminate\Support\Str::plural('event', $organizer['events']) }}</span>
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
