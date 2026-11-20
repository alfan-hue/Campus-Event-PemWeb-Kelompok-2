@extends('layouts.app')

@section('content')
@php
    // Sample data so the page renders on its own.
    // In your controller, pass $category, then delete this block.
    $category = $category ?? [
        'id' => 5,
        'name' => 'Arts & Culture',
        'slug' => 'arts-culture',
        'description' => 'Exhibitions, performances, music nights, and creative showcases.',
        'events_count' => 7,
        'is_active' => true,
        'created_at' => '09 Sep 2026',
    ];
@endphp

<div class="container py-4">
    <div class="row g-4">

        <div class="col-lg-3">
            @include('admin_dashboard.sidebar')
        </div>

        <div class="col-lg-9">

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="{{ url('/admin/categories') }}" class="text-decoration-none">Categories</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit {{ $category['name'] }}</li>
                </ol>
            </nav>

            <div class="mb-4">
                <h1 class="h3 fw-bold mb-1">Edit category</h1>
                <p class="text-secondary mb-0">Changes apply to every event in this category.</p>
            </div>

            <div class="row">
                <div class="col-xl-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            @include('admin_dashboard.categoies.form', [
                                'action' => url('/admin/categories/' . $category['id']),
                                'method' => 'PUT',
                                'category' => $category,
                                'submitLabel' => 'Save changes',
                            ])
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 mt-4 mt-xl-0">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h2 class="h6 fw-bold mb-3">About this category</h2>
                            <dl class="small mb-0">
                                <div class="d-flex justify-content-between mb-2">
                                    <dt class="fw-normal text-secondary">Events</dt>
                                    <dd class="fw-semibold mb-0">{{ $category['events_count'] }}</dd>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <dt class="fw-normal text-secondary">Created</dt>
                                    <dd class="fw-semibold mb-0">{{ $category['created_at'] }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
