<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminCategoryController extends Controller
{
    // GET /admin/categories
    public function index()
    {
        return view('admin_dashboard.categoies.index');
    }

    // GET /admin/categories/create
    public function create()
    {
        return view('admin_dashboard.categoies.create');
    }

    // POST /admin/categories
    // TODO: validate and save to the database later.
    public function store(Request $request)
    {
        return redirect()->route('admin.categories.index')
            ->with('success', 'Preview only: nothing was saved yet.');
    }

    // GET /admin/categories/{category}/edit
    public function edit($category)
    {
        return view('admin_dashboard.categoies.edit');
    }

    // PUT /admin/categories/{category}
    // TODO: validate and update in the database later.
    public function update(Request $request, $category)
    {
        return redirect()->route('admin.categories.index')
            ->with('success', 'Preview only: nothing was saved yet.');
    }

    // DELETE /admin/categories/{category}
    // TODO: delete from the database later.
    public function destroy($category)
    {
        return redirect()->route('admin.categories.index')
            ->with('success', 'Preview only: nothing was deleted yet.');
    }
}
