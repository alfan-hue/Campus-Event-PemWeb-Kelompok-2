<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->pluck('name');

        $baseQuery = Event::with(['category', 'organizer'])
            ->where('status', 'published');

        if ($request->filled('q')) {
            $keyword = $request->input('q');
            $baseQuery->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%")
                    ->orWhere('location', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('category')) {
            $categoryName = $request->input('category');
            $baseQuery->whereHas('category', function ($q) use ($categoryName) {
                $q->where('name', $categoryName);
            });
        }

        $today = now()->toDateString();
        $todayCarbon = now()->startOfDay();

        // 1. Ongoing events: (start_date <= today AND (end_date >= today OR end_date is null)) OR (start_date is null AND date = today)
        $ongoingQuery = (clone $baseQuery)->where(function ($q) use ($today) {
            $q->where(function ($sub) use ($today) {
                $sub->whereNotNull('start_date')
                    ->where('start_date', '<=', $today)
                    ->where(function ($endSub) use ($today) {
                        $endSub->whereNull('end_date')
                            ->orWhere('end_date', '>=', $today);
                    });
            })->orWhere(function ($sub) use ($today) {
                $sub->whereNull('start_date')
                    ->where('date', '=', $today);
            });
        });

        // 2. Upcoming events: (start_date > today) OR (start_date is null AND date > today)
        $upcomingQuery = (clone $baseQuery)->where(function ($q) use ($today) {
            $q->where(function ($sub) use ($today) {
                $sub->whereNotNull('start_date')
                    ->where('start_date', '>', $today);
            })->orWhere(function ($sub) use ($today) {
                $sub->whereNull('start_date')
                    ->where('date', '>', $today);
            });
        });

        $transformEvent = function ($event) use ($todayCarbon) {
            $startDate = $event->start_date ?? $event->date;
            $endDate = $event->end_date ?? $startDate;

            if ($startDate && $todayCarbon->lt($startDate->startOfDay())) {
                $timingStatus = 'upcoming';
            } elseif ($endDate && $todayCarbon->gt($endDate->endOfDay())) {
                $timingStatus = 'past';
            } else {
                $timingStatus = 'ongoing';
            }

            $dateStr = $startDate ? $startDate->format('d M Y') : '-';
            if ($startDate && $endDate && $startDate->format('Y-m-d') !== $endDate->format('Y-m-d')) {
                $dateStr .= ' – ' . $endDate->format('d M Y');
            }

            $timeStr = '-';
            if ($event->start_time && $event->end_time) {
                $timeStr = substr($event->start_time, 0, 5) . ' – ' . substr($event->end_time, 0, 5);
            } elseif ($event->start_time) {
                $timeStr = substr($event->start_time, 0, 5);
            }

            return [
                'id' => $event->id,
                'title' => $event->title,
                'category' => $event->category?->name ?? 'Uncategorized',
                'status' => $timingStatus,
                'date' => $dateStr,
                'time' => $timeStr,
                'location' => $event->location,
                'organizer' => $event->organizer?->name ?? 'Campus Community',
                'description' => $event->description,
                'registration_url' => $event->registration_url,
                'image' => $event->poster ? asset('storage/' . $event->poster) : null,
            ];
        };

        $ongoingEvents = $ongoingQuery->latest('date')->get()->map($transformEvent)->all();
        $upcomingEvents = $upcomingQuery->orderBy('date')->get()->map($transformEvent)->all();

        $sections = [
            [
                'title' => 'Happening Now',
                'subtitle' => 'Ongoing events you can join today.',
                'badge' => 'Ongoing',
                'badgeClass' => 'bg-success',
                'events' => $ongoingEvents,
            ],
            [
                'title' => 'Upcoming Events',
                'subtitle' => 'Mark your calendar for what is coming next.',
                'badge' => 'Upcoming',
                'badgeClass' => 'bg-primary',
                'events' => $upcomingEvents,
            ],
        ];

        return view('home', compact('categories', 'sections', 'ongoingEvents', 'upcomingEvents'));
    }
}