@extends('layouts.app')

@section('content')
@php
    // Sample data so the page renders on its own.
    // In your controller, pass $categories (array, collection or paginator), then delete this block.
    $categories = $categories ?? [
        ['id' => 1, 'name' => 'Seminar', 'slug' => 'seminar', 'events_count' => 14, 'is_active' => true, 'created_at' => '02 Sep 2026'],
        ['id' => 2, 'name' => 'Workshop', 'slug' => 'workshop', 'events_count' => 11, 'is_active' => true, 'created_at' => '02 Sep 2026'],
        ['id' => 3, 'name' => 'Competition', 'slug' => 'competition', 'events_count' => 8, 'is_active' => true, 'created_at' => '02 Sep 2026'],
        ['id' => 4, 'name' => 'Sports', 'slug' => 'sports', 'events_count' => 5, 'is_active' => true, 'created_at' => '09 Sep 2026'],
        ['id' => 5, 'name' => 'Arts & Culture', 'slug' => 'arts-culture', 'events_count' => 7, 'is_active' => true, 'created_at' => '09 Sep 2026'],
        ['id' => 6, 'name' => 'Community Service', 'slug' => 'community-service', 'events_count' => 3, 'is_active' => false, 'created_at' => '21 Sep 2026'],
    ];
@endphp

<div class="container py-4">
    <div class="row g-4">

        <div class="col-lg-3">
            @include('admin_dashboard.sidebar')
        </div>

        <div class="col-lg-9">

            {{-- Page header --}}
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h1 class="h3 fw-bold mb-1">Categories</h1>
                    <p class="text-secondary mb-0">Organizers pick one of these when they add an event, and students use them to filter.</p>
                </div>
                <a href="{{ url('/admin/categories/create') }}" class="btn btn-primary">
                    <i class='bx bx-plus align-middle'></i> Add category
                </a>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    {{-- Toolbar --}}
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <div class="position-relative" style="min-width: 16rem;">
                            <label for="categorySearch" class="visually-hidden">Search categories</label>
                            <i class='bx bx-search position-absolute top-50 translate-middle-y text-secondary' style="left: .75rem;"></i>
                            <input type="search" id="categorySearch" class="form-control" style="padding-left: 2.25rem;" placeholder="Search categories">
                        </div>
                        <span class="small text-secondary" id="categoryCount" aria-live="polite">{{ count($categories) }} {{ \Illuminate\Support\Str::plural('category', count($categories)) }}</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Slug</th>
                                    <th scope="col" class="text-center">Events</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Created</th>
                                    <th scope="col" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="categoryRows">
                                @foreach ($categories as $category)
                                    <tr class="category-row" data-name="{{ mb_strtolower($category['name']) }}">
                                        <td class="fw-semibold">{{ $category['name'] }}</td>
                                        <td><code>{{ $category['slug'] }}</code></td>
                                        <td class="text-center">{{ $category['events_count'] }}</td>
                                        <td>
                                            @if ($category['is_active'])
                                                <span class="badge bg-success bg-opacity-10 text-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary">Hidden</span>
                                            @endif
                                        </td>
                                        <td class="text-nowrap text-secondary">{{ $category['created_at'] }}</td>
                                        <td class="text-end text-nowrap">
                                            <a href="{{ url('/admin/categories/' . $category['id'] . '/edit') }}" class="btn btn-sm btn-outline-primary" aria-label="Edit {{ $category['name'] }}">
                                                <i class='bx bx-edit'></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete"
                                                    data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                    data-action="{{ url('/admin/categories/' . $category['id']) }}"
                                                    data-name="{{ $category['name'] }}"
                                                    data-count="{{ $category['events_count'] }}"
                                                    aria-label="Delete {{ $category['name'] }}">
                                                <i class='bx bx-trash'></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach

                                {{-- Shown by jQuery when the search has no match --}}
                                <tr id="noResults" style="display: none;">
                                    <td colspan="6" class="text-center text-secondary py-4">No category matches your search.</td>
                                </tr>

                                @if (count($categories) === 0)
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <i class='bx bx-category fs-1 text-primary'></i>
                                            <p class="fw-semibold mb-1">No categories yet</p>
                                            <p class="text-secondary mb-3">Add your first category so organizers can tag their events.</p>
                                            <a href="{{ url('/admin/categories/create') }}" class="btn btn-primary btn-sm">Add category</a>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
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
                    <h2 class="modal-title h5 fw-bold" id="deleteModalLabel">Delete category</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">Delete <strong id="deleteName"></strong>? This can't be undone.</p>
                    <div class="alert alert-warning small mb-0" id="deleteWarning" style="display: none;"></div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete category</button>
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

            // Live search
            $('#categorySearch').on('input', function () {
                var term = $.trim($(this).val()).toLowerCase();
                var visible = 0;

                $('.category-row').each(function () {
                    var match = $(this).data('name').toString().indexOf(term) !== -1;
                    $(this).toggle(match);
                    if (match) { visible++; }
                });

                $('#noResults').toggle(visible === 0 && $('.category-row').length > 0);
                $('#categoryCount').text(visible + (visible === 1 ? ' category' : ' categories'));
            });

            // Fill the delete modal with the clicked category
            $('.btn-delete').on('click', function () {
                var count = parseInt($(this).data('count'), 10) || 0;

                $('#deleteForm').attr('action', $(this).data('action'));
                $('#deleteName').text($(this).data('name'));

                if (count > 0) {
                    $('#deleteWarning')
                        .text('This category is used by ' + count + (count === 1 ? ' event.' : ' events.'))
                        .show();
                } else {
                    $('#deleteWarning').hide();
                }
            });
        })(window.jQuery);
    });
</script>
@endsection
