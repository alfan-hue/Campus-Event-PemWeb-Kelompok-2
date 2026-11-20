{{--
    Shared event form (partial used by create and edit).
    Usage:
        @include('organizer_dashboard.form', [
            'action' => url('/organizer/events'),
            'method' => 'POST',            // 'PUT' when editing
            'event' => null,               // array when editing
            'categories' => [...],         // list of category names
            'submitLabel' => 'Publish event',
        ])
--}}
@php
    $event = $event ?? [];
    $currentImage = $event['image'] ?? null;
@endphp

<form method="POST" action="{{ $action }}" enctype="multipart/form-data" novalidate>
    @csrf
    @if (strtoupper($method) !== 'POST')
        @method($method)
    @endif

    <div class="row g-4">

        {{-- Left column: details --}}
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="h5 fw-bold mb-4">Event details</h2>

                    <div class="mb-3">
                        <label for="title" class="form-label fw-semibold">Title</label>
                        <input id="title" type="text" name="title" maxlength="120" required
                               class="form-control form-control-lg @error('title') is-invalid @enderror"
                               value="{{ old('title', $event['title'] ?? '') }}"
                               placeholder="For example, Intro to Web Development Workshop">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="category" class="form-label fw-semibold">Category</label>
                        <select id="category" name="category" required class="form-select @error('category') is-invalid @enderror">
                            <option value="">Choose a category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category }}" @selected(old('category', $event['category'] ?? '') === $category)>{{ $category }}</option>
                            @endforeach
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">Description</label>
                        <textarea id="description" name="description" rows="6" maxlength="1000" required
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="What happens, who it is for, and what to bring.">{{ old('description', $event['description'] ?? '') }}</textarea>
                        <div class="d-flex justify-content-between">
                            <div class="form-text">Start with the key facts. Event cards show only the first lines.</div>
                            <div class="form-text"><span id="descCount">0</span>/1000</div>
                        </div>
                        @error('description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="start_date" class="form-label fw-semibold">Start date</label>
                            <input id="start_date" type="date" name="start_date" required
                                   class="form-control @error('start_date') is-invalid @enderror"
                                   value="{{ old('start_date', $event['start_date'] ?? '') }}">
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="end_date" class="form-label fw-semibold">End date</label>
                            <input id="end_date" type="date" name="end_date" required
                                   class="form-control @error('end_date') is-invalid @enderror"
                                   value="{{ old('end_date', $event['end_date'] ?? '') }}">
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="start_time" class="form-label fw-semibold">Start time</label>
                            <input id="start_time" type="time" name="start_time" required
                                   class="form-control @error('start_time') is-invalid @enderror"
                                   value="{{ old('start_time', $event['start_time'] ?? '') }}">
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="end_time" class="form-label fw-semibold">End time</label>
                            <input id="end_time" type="time" name="end_time" required
                                   class="form-control @error('end_time') is-invalid @enderror"
                                   value="{{ old('end_time', $event['end_time'] ?? '') }}">
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="location" class="form-label fw-semibold">Location</label>
                        <input id="location" type="text" name="location" maxlength="150" required
                               class="form-control @error('location') is-invalid @enderror"
                               value="{{ old('location', $event['location'] ?? '') }}"
                               placeholder="Building and room, or a clear meeting point">
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Right column: image + registration --}}
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h2 class="h5 fw-bold mb-3">Event image</h2>

                    <img id="imagePreview"
                         src="{{ $currentImage ?? 'https://placehold.co/600x400' }}"
                         data-default="{{ $currentImage ?? 'https://placehold.co/600x400' }}"
                         alt="Preview of the event image"
                         class="w-100 rounded-3 mb-3"
                         style="aspect-ratio: 3 / 2; object-fit: cover;">

                    <label for="image" class="form-label fw-semibold visually-hidden">Upload an image</label>
                    <input id="image" type="file" name="image" accept="image/png,image/jpeg,image/webp"
                           class="form-control @error('image') is-invalid @enderror">
                    <div class="form-text">JPG, PNG or WebP. A 3:2 image such as 1200×800 fits the cards best.</div>
                    @error('image')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h2 class="h5 fw-bold mb-3">Registration</h2>

                    <label for="registration_url" class="form-label fw-semibold">Registration link</label>
                    <input id="registration_url" type="url" name="registration_url" required
                           class="form-control @error('registration_url') is-invalid @enderror"
                           value="{{ old('registration_url', $event['registration_url'] ?? '') }}"
                           placeholder="https://">
                    <div class="form-text">Students open this link when they select Register. Test it before you publish.</div>
                    @error('registration_url')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">{{ $submitLabel }}</button>
                <a href="{{ url('/organizer') }}" class="btn btn-light">Cancel</a>
            </div>
        </div>

    </div>
</form>

<script>
    // Wait for the page to load so jQuery (from layouts/app.blade.php) is ready.
    window.addEventListener('load', function () {
        (function ($) {
            if (!$) { return; }

            // Description counter
            var $desc = $('#description');
            function updateCount() { $('#descCount').text($desc.val().length); }
            $desc.on('input', updateCount);
            updateCount();

            // Image preview
            $('#image').on('change', function () {
                var file = this.files && this.files[0];
                var $preview = $('#imagePreview');

                if (!file) {
                    $preview.attr('src', $preview.data('default'));
                    return;
                }

                var reader = new FileReader();
                reader.onload = function (e) { $preview.attr('src', e.target.result); };
                reader.readAsDataURL(file);
            });

            // End date can't be earlier than the start date
            $('#start_date').on('change', function () {
                $('#end_date').attr('min', $(this).val());
                if (!$('#end_date').val() || $('#end_date').val() < $(this).val()) {
                    $('#end_date').val($(this).val());
                }
            });
        })(window.jQuery);
    });
</script>
