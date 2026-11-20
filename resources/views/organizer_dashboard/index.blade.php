@extends('layouts.app')

@section('content')
@php
    // Sample data so the page renders on its own.
    // In your controller, pass $myEvents (the logged-in organizer's events), then delete this block.
    $statusMap = [
        'ongoing' => ['Ongoing', 'bg-success'],
        'upcoming' => ['Upcoming', 'bg-primary'],
        'past' => ['Ended', 'bg-secondary'],
    ];

    $events = collect($myEvents);
    $counts = [
        'all' => $events->count(),
        'ongoing' => $events->where('status', 'ongoing')->count(),
        'upcoming' => $events->where('status', 'upcoming')->count(),
        'past' => $events->where('status', 'past')->count(),
    ];
    $statusMap = [
        'ongoing' => ['Ongoing', 'bg-success'],
        'upcoming' => ['Upcoming', 'bg-primary'],
        'past' => ['Ended', 'bg-secondary'],
    ];

    $events = collect($myEvents);
    $counts = [
        'all' => $events->count(),
        'ongoing' => $events->where('status', 'ongoing')->count(),
        'upcoming' => $events->where('status', 'upcoming')->count(),
        'past' => $events->where('status', 'past')->count(),
    ];

    $firstName = \Illuminate\Support\Str::of(Auth::user()?->name ?? 'there')->explode(' ')->first();

    $statCards = [
        ['label' => 'Total events', 'value' => $counts['all'], 'icon' => 'bx-calendar-event'],
        ['label' => 'Ongoing', 'value' => $counts['ongoing'], 'icon' => 'bx-pulse'],
        ['label' => 'Upcoming', 'value' => $counts['upcoming'], 'icon' => 'bx-calendar-star'],
        ['label' => 'Ended', 'value' => $counts['past'], 'icon' => 'bx-calendar-check'],
    ];

    $filters = ['all' => 'All', 'ongoing' => 'Ongoing', 'upcoming' => 'Upcoming', 'past' => 'Ended'];
@endphp

<div class="container py-4">
    <div class="row g-4">

        <div class="col-lg-3">
            @include('organizer_dashboard.sidebar')
        </div>

        <div class="col-lg-9">

            {{-- Welcome banner --}}
            <div class="hero text-white rounded-4 shadow p-4 p-lg-5 mb-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div>
                        <h1 class="h3 fw-bold mb-1">Hello, {{ $firstName }}</h1>
                        <p class="mb-0" style="opacity: .9;">Add and update the events you share with the whole campus.</p>
                    </div>
                    <a href="{{ url('/organizer/events/create') }}" class="btn btn-light text-primary fw-semibold">
                        <i class='bx bx-plus align-middle'></i> Add event
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

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

            {{-- Events list --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h2 class="h5 fw-bold mb-3">My events</h2>

                    @if ($counts['all'] > 0)
                        {{-- Status filter (switched with jQuery) --}}
                        <div class="d-flex flex-wrap gap-2 mb-3" role="group" aria-label="Filter events by status">
                            @foreach ($filters as $key => $label)
                                <button type="button" class="btn btn-sm rounded-pill filter-pill {{ $key === 'all' ? 'btn-primary' : 'btn-outline-primary' }}" data-filter="{{ $key }}">
                                    {{ $label }} <span class="opacity-75">{{ $counts[$key] }}</span>
                                </button>
                            @endforeach
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">Event</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Registration</th>
                                        <th scope="col" class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($myEvents as $event)
                                        @php [$label, $class] = $statusMap[$event['status']] ?? $statusMap['upcoming']; @endphp
                                        <tr class="event-row" data-status="{{ $event['status'] }}">
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <img src="{{ $event['image'] ?? 'https://placehold.co/600x400' }}" alt="" class="event-thumb">
                                                    <div>
                                                        <div class="fw-semibold">{{ $event['title'] }}</div>
                                                        <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary">{{ $event['category'] }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-nowrap">{{ $event['date'] }}</td>
                                            <td><span class="badge {{ $class }}">{{ $label }}</span></td>
                                            <td>
                                                <a href="{{ $event['registration_url'] }}" target="_blank" rel="noopener noreferrer" class="small text-decoration-none">
                                                    Open link <i class='bx bx-link-external align-middle'></i>
                                                </a>
                                            </td>
                                            <td class="text-end text-nowrap">
                                                <a href="{{ url('/events/' . $event['id']) }}" class="btn btn-sm btn-outline-secondary" aria-label="View {{ $event['title'] }} on the site">
                                                    <i class='bx bx-show'></i>
                                                </a>
                                                <a href="{{ url('/organizer/events/' . $event['id'] . '/edit') }}" class="btn btn-sm btn-outline-primary" aria-label="Edit {{ $event['title'] }}">
                                                    <i class='bx bx-edit'></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete"
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                        data-action="{{ url('/organizer/events/' . $event['id']) }}"
                                                        data-name="{{ $event['title'] }}"
                                                        aria-label="Delete {{ $event['title'] }}">
                                                    <i class='bx bx-trash'></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach

                                    <tr id="noResults" style="display: none;">
                                        <td colspan="5" class="text-center text-secondary py-4">You have no events with this status.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class='bx bx-calendar-plus fs-1 text-primary'></i>
                            <p class="fw-semibold mb-1">You haven't added any events yet</p>
                            <p class="text-secondary mb-3">Your first event takes a few minutes and appears on the home page once saved.</p>
                            <a href="{{ url('/organizer/events/create') }}" class="btn btn-primary">Add your first event</a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Guide link --}}
            <div class="hero-light rounded-4 p-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h2 class="h6 fw-bold mb-1">New to Campus Event?</h2>
                    <p class="small text-secondary mb-0">The organizer guide explains each field and how to reach more students.</p>
                </div>
                <a href="{{ url('/organizers/guide') }}" class="btn btn-primary btn-sm px-3">Read the guide</a>
            </div>

        </div>
    </div>
</div>

{{-- Delete confirmation --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form method="POST" id="deleteForm" action="">
                @csrf
                @method('DELETE')

                <div class="modal-header border-0 pb-0">
                    <h2 class="modal-title h5 fw-bold" id="deleteModalLabel">Delete event</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Delete <strong id="deleteName"></strong>? It will be removed from Campus Event and this can't be undone.</p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Keep event</button>
                    <button type="submit" class="btn btn-danger">Delete event</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Wait for the page to load so jQuery (from layouts/app.blade.php) is ready.
    window.addEventListener('load', function () {
        (function ($) {
            if (!$) { return; }

            // Status filter
            $('.filter-pill').on('click', function () {
                var filter = $(this).data('filter');
                var visible = 0;

                $('.filter-pill').removeClass('btn-primary').addClass('btn-outline-primary');
                $(this).removeClass('btn-outline-primary').addClass('btn-primary');

                $('.event-row').each(function () {
                    var match = filter === 'all' || $(this).data('status') === filter;
                    $(this).toggle(match);
                    if (match) { visible++; }
                });

                $('#noResults').toggle(visible === 0);
            });

            // Fill the delete modal with the clicked event
            $('.btn-delete').on('click', function () {
                $('#deleteForm').attr('action', $(this).data('action'));
                $('#deleteName').text($(this).data('name'));
            });
        })(window.jQuery);
    });
</script>
@endsection
