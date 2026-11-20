{{--
    Organizer sidebar, shared by every organizer_dashboard page.
    Usage: @include('organizer_dashboard.sidebar')
--}}
@php
    $organizerNav = [
        ['label' => 'My events', 'icon' => 'bx-calendar-event', 'url' => url('/organizer'), 'active' => request()->is('organizer') || (request()->is('organizer/events/*/edit'))],
        ['label' => 'Add event', 'icon' => 'bx-calendar-plus', 'url' => url('/organizer/events/create'), 'active' => request()->is('organizer/events/create')],
        ['label' => 'Profile', 'icon' => 'bx-user', 'url' => url('/profile'), 'active' => request()->is('profile*')],
        ['label' => 'Organizer guide', 'icon' => 'bx-book-open', 'url' => url('/organizers/guide'), 'active' => request()->is('organizers/guide')],
    ];
@endphp

@once
    <style>
        .hero { background: linear-gradient(135deg, #0d6efd 0%, #0a4fc4 100%); }
        .hero-light {
            background: linear-gradient(135deg, #e7f1ff 0%, #f5f9ff 100%);
            border: 1px solid #cfe2ff;
        }
        .icon-circle { width: 2.75rem; height: 2.75rem; flex-shrink: 0; }
        .dash-nav .nav-link {
            display: flex;
            align-items: center;
            gap: .6rem;
            color: #495057;
            font-weight: 600;
            border-radius: .5rem;
            white-space: nowrap;
        }
        .dash-nav .nav-link:hover { background: #e7f1ff; color: #0d6efd; }
        .dash-nav .nav-link.active { background: #0d6efd; color: #fff; }
        .dash-nav .nav-link i { font-size: 1.25rem; }
        .event-thumb { width: 3.5rem; height: 3.5rem; object-fit: cover; border-radius: .5rem; flex-shrink: 0; }
    </style>
@endonce

<aside class="card border-0 shadow-sm">
    <div class="card-body p-3">
        <div class="px-2 pt-1 pb-3">
            <div class="fw-bold text-truncate">{{ Auth::user()?->name ?? 'Your organization' }}</div>
            <div class="small text-secondary text-truncate">{{ Auth::user()?->email ?? 'organizer@campus.ac.id' }}</div>
        </div>

        <nav class="nav flex-nowrap flex-lg-column overflow-auto dash-nav gap-1" aria-label="Organizer navigation">
            @foreach ($organizerNav as $item)
                <a href="{{ $item['url'] }}" class="nav-link {{ $item['active'] ? 'active' : '' }}" @if ($item['active']) aria-current="page" @endif>
                    <i class='bx {{ $item['icon'] }}'></i> {{ $item['label'] }}
                </a>
            @endforeach
        </nav>
    </div>
</aside>
