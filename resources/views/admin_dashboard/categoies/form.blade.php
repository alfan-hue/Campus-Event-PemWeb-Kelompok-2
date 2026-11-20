{{--
    Shared category form (partial used by create and edit).
    Usage:
        @include('admin_dashboard.categoies.form', [
            'action' => url('/admin/categories'),
            'method' => 'POST',            // 'PUT' when editing
            'category' => null,            // array when editing
            'submitLabel' => 'Create category',
        ])
--}}
@php
    $category = $category ?? [];
    $isActive = old('is_active', $category['is_active'] ?? true);
@endphp

<form method="POST" action="{{ $action }}" novalidate>
    @csrf
    @if (strtoupper($method) !== 'POST')
        @method($method)
    @endif

    <div class="mb-3">
        <label for="name" class="form-label fw-semibold">Name</label>
        <input id="name" type="text" name="name"
               class="form-control form-control-lg @error('name') is-invalid @enderror"
               value="{{ old('name', $category['name'] ?? '') }}" maxlength="60" required autofocus>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="slug" class="form-label fw-semibold">Slug</label>
        <input id="slug" type="text" name="slug"
               class="form-control @error('slug') is-invalid @enderror"
               value="{{ old('slug', $category['slug'] ?? '') }}" maxlength="80" required>
        <div class="form-text">Used in links, for example <code>arts-culture</code>. It fills in from the name, and you can edit it.</div>
        @error('slug')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="description" class="form-label fw-semibold">Description <span class="fw-normal text-secondary">(optional)</span></label>
        <textarea id="description" name="description" rows="3" maxlength="200"
                  class="form-control @error('description') is-invalid @enderror">{{ old('description', $category['description'] ?? '') }}</textarea>
        <div class="d-flex justify-content-between">
            <div class="form-text">A short note about which events belong here.</div>
            <div class="form-text"><span id="descCount">0</span>/200</div>
        </div>
        @error('description')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-check form-switch mb-4">
        <input type="hidden" name="is_active" value="0">
        <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" @checked($isActive)>
        <label class="form-check-label fw-semibold" for="is_active">Show this category to organizers and students</label>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary px-4">{{ $submitLabel }}</button>
        <a href="{{ url('/admin/categories') }}" class="btn btn-light">Cancel</a>
    </div>
</form>

<script>
    // Wait for the page to load so jQuery (from layouts/app.blade.php) is ready.
    window.addEventListener('load', function () {
        (function ($) {
            if (!$) { return; }

            var $name = $('#name');
            var $slug = $('#slug');
            var $desc = $('#description');
            var slugTouched = $.trim($slug.val()) !== '';

            function slugify(text) {
                return text.toLowerCase()
                    .replace(/&/g, ' ')
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            }

            // Slug follows the name until the person edits the slug themselves
            $name.on('input', function () {
                if (!slugTouched) { $slug.val(slugify($name.val())); }
            });
            $slug.on('input', function () { slugTouched = true; });

            // Description counter
            function updateCount() { $('#descCount').text($desc.val().length); }
            $desc.on('input', updateCount);
            updateCount();
        })(window.jQuery);
    });
</script>
