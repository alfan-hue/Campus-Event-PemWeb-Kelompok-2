<?php

namespace App\Http\Controllers;

class OrganizerDashboardController extends Controller
{
    // GET /organizer  (view: organizer_dashboard.index)
    public function index()
    {
        return view('organizer_dashboard.index');
    }
}
