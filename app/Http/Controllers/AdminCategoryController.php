<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminCategoryController extends Controller
{
    // GET /admin/categories
    public function index()
    {
        $categories = Category::withCount('events')
            ->orderBy('name')
            ->get();

        return view('admin_dashboard.categoies.index', compact('categories'));
    }

    // GET /admin/categories/create
    public function create()
    {
        return view('admin_dashboard.categoies.create');
    }

    // POST /admin/categories
    public function store(Request $request)
    {
        $request->merge([
            'slug' => Str::slug($request->input('slug') ?: $request->input('name')),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:60', 'unique:categories,name'],
            'slug' => ['required', 'string', 'max:80', 'unique:categories,slug'],
            'description' => ['nullable', 'string', 'max:200'],
            'is_active' => ['required', 'boolean'],
        ]);

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category "' . $validated['name'] . '" created successfully.');
    }

    // GET /admin/categories/{category}/edit
    public function edit(Category $category)
    {
        return view('admin_dashboard.categoies.edit', compact('category'));
    }

    // PUT /admin/categories/{category}
    public function update(Request $request, Category $category)
    {
        $request->merge([
            'slug' => Str::slug($request->input('slug') ?: $request->input('name')),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:60', Rule::unique('categories', 'name')->ignore($category->id)],
            'slug' => ['required', 'string', 'max:80', Rule::unique('categories', 'slug')->ignore($category->id)],
            'description' => ['nullable', 'string', 'max:200'],
            'is_active' => ['required', 'boolean'],
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category "' . $category->name . '" updated successfully.');
    }

    // DELETE /admin/categories/{category}
    public function destroy(Category $category)
    {
        if ($category->events()->exists()) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete "' . $category->name . '" because it is still used by existing events.');
        }

        $name = $category->name;
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category "' . $name . '" deleted successfully.');
    }
}