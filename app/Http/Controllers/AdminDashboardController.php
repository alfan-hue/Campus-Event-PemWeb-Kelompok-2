<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\User;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard with data from the database.
     */
    public function index()
    {
        // Summary cards.
        $stats = [
            'events' => Event::count(),
            'pending' => Event::where('status', 'pending')->count(),
            'organizers' => User::where('is_admin', false)->count(),
            'categories' => Category::count(),
        ];

        // Latest events, including their organizer and category names.
        $recentEvents = Event::with([
            'organizer:id,name',
            'category:id,name',
        ])
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        // Number of events in each category.
        $categorySummary = Category::withCount('events')
            ->orderByDesc('events_count')
            ->orderBy('name')
            ->get();

        // Latest users who are not admins. In this project, these are organizers.
        $recentOrganizers = User::where('is_admin', false)
            ->withCount('events')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        return view('admin_dashboard.index', compact(
            'stats',
            'recentEvents',
            'categorySummary',
            'recentOrganizers'
        ));
    }
}
