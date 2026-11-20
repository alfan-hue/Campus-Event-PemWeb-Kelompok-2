@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row g-4">

        <div class="col-lg-3">
            @include('admin_dashboard.sidebar')
        </div>

        <div class="col-lg-9">

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="{{ url('/admin/categories') }}" class="text-decoration-none">Categories</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add category</li>
                </ol>
            </nav>

            <div class="mb-4">
                <h1 class="h3 fw-bold mb-1">Add category</h1>
                <p class="text-secondary mb-0">Create a new category for organizers to use on their events.</p>
            </div>

            <div class="row">
                <div class="col-xl-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            @include('admin_dashboard.categoies.form', [
                                'action' => url('/admin/categories'),
                                'method' => 'POST',
                                'category' => null,
                                'submitLabel' => 'Create category',
                            ])
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 mt-4 mt-xl-0">
                    <div class="hero-light rounded-4 p-4">
                        <h2 class="h6 fw-bold mb-2">Tips for good categories</h2>
                        <ul class="small text-secondary ps-3 mb-0 d-grid gap-2">
                            <li>Use short names students will recognize, like “Workshop” or “Sports”.</li>
                            <li>Keep the list small so filtering stays easy.</li>
                            <li>Hide a category instead of deleting it if events still use it.</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
