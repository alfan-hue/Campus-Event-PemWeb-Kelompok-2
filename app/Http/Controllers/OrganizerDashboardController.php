<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class OrganizerDashboardController extends Controller
{
    public function index()
    {
        $events = Event::with('category')
            ->where('organizer_id', Auth::id())
            ->latest()
            ->get();

        $today = now()->startOfDay();

        $myEvents = $events->map(function ($event) use ($today) {
            $startDate = $event->start_date ?? $event->date;
            $endDate = $event->end_date ?? $startDate;

            if ($today->lt($startDate)) {
                $status = 'upcoming';
            } elseif ($today->gt($endDate)) {
                $status = 'past';
            } else {
                $status = 'ongoing';
            }

            $date = $startDate->format('d M Y');

            if ($startDate->format('Y-m-d') !== $endDate->format('Y-m-d')) {
                $date .= ' – ' . $endDate->format('d M Y');
            }

            return [
                'id' => $event->id,
                'title' => $event->title,
                'category' => $event->category?->name ?? '-',
                'status' => $status,
                'date' => $date,
                'registration_url' => $event->registration_url,
                'image' => $event->poster
                    ? asset('storage/' . $event->poster)
                    : null,
            ];
        });

        return view('organizer_dashboard.index', compact('myEvents'));
    }
}
