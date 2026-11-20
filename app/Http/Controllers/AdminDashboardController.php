<?php

namespace App\Http\Controllers;

class AdminDashboardController extends Controller
{
    // GET /admin  (view: admin_dashboard.index)
    public function index()
    {
        return view('admin_dashboard.index');
    }
}
