<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    // GET /events  (view: event.index)
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->pluck('name');

        $query = Event::with(['category', 'organizer'])
            ->where('status', 'published');

        // Search by keyword in title or description
        if ($request->filled('q')) {
            $keyword = $request->input('q');
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%")
                  ->orWhere('location', 'like', "%{$keyword}%");
            });
        }

        // Filter by category name
        if ($request->filled('category')) {
            $categoryName = $request->input('category');
            $query->whereHas('category', function ($q) use ($categoryName) {
                $q->where('name', $categoryName);
            });
        }

        // Filter by event schedule status: ongoing, upcoming, past
        $today = now()->toDateString();
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'upcoming') {
                $query->where(function ($q) use ($today) {
                    $q->where(function ($sub) use ($today) {
                        $sub->whereNotNull('start_date')
                            ->where('start_date', '>', $today);
                    })->orWhere(function ($sub) use ($today) {
                        $sub->whereNull('start_date')
                            ->where('date', '>', $today);
                    });
                });
            } elseif ($status === 'past') {
                $query->where(function ($q) use ($today) {
                    $q->where(function ($sub) use ($today) {
                        $sub->whereNotNull('end_date')
                            ->where('end_date', '<', $today);
                    })->orWhere(function ($sub) use ($today) {
                        $sub->whereNull('end_date')
                            ->whereNotNull('start_date')
                            ->where('start_date', '<', $today);
                    })->orWhere(function ($sub) use ($today) {
                        $sub->whereNull('end_date')
                            ->whereNull('start_date')
                            ->where('date', '<', $today);
                    });
                });
            } elseif ($status === 'ongoing') {
                $query->where(function ($q) use ($today) {
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
            }
        }

        $events = $query->latest('date')->paginate(9)->withQueryString();

        // Transform paginated items so view / card can consume uniform keys
        $todayCarbon = now()->startOfDay();
        $events->getCollection()->transform(function ($event) use ($todayCarbon) {
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
        });

        return view('event.index', compact('events', 'categories'));
    }

    // GET /events/{event}  (view: event.show)
    public function show($id)
    {
        $eventModel = Event::with(['category', 'organizer'])
            ->where('status', 'published')
            ->findOrFail($id);

        $todayCarbon = now()->startOfDay();
        $startDate = $eventModel->start_date ?? $eventModel->date;
        $endDate = $eventModel->end_date ?? $startDate;

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
        if ($eventModel->start_time && $eventModel->end_time) {
            $timeStr = substr($eventModel->start_time, 0, 5) . ' – ' . substr($eventModel->end_time, 0, 5);
        } elseif ($eventModel->start_time) {
            $timeStr = substr($eventModel->start_time, 0, 5);
        }

        $event = [
            'id' => $eventModel->id,
            'title' => $eventModel->title,
            'category' => $eventModel->category?->name ?? 'Uncategorized',
            'status' => $timingStatus,
            'date' => $dateStr,
            'time' => $timeStr,
            'location' => $eventModel->location,
            'organizer' => $eventModel->organizer?->name ?? 'Campus Community',
            'description' => $eventModel->description,
            'registration_url' => $eventModel->registration_url,
            'image' => $eventModel->poster ? asset('storage/' . $eventModel->poster) : null,
        ];

        // Related events in the same category
        $relatedEvents = Event::with('category')
            ->where('status', 'published')
            ->where('id', '!=', $eventModel->id)
            ->where('category_id', $eventModel->category_id)
            ->latest('date')
            ->limit(3)
            ->get()
            ->map(function ($rel) use ($todayCarbon) {
                $start = $rel->start_date ?? $rel->date;
                $end = $rel->end_date ?? $start;
                $st = ($start && $todayCarbon->lt($start->startOfDay()))
                    ? 'upcoming'
                    : (($end && $todayCarbon->gt($end->endOfDay())) ? 'past' : 'ongoing');

                $dStr = $start ? $start->format('d M Y') : '-';
                if ($start && $end && $start->format('Y-m-d') !== $end->format('Y-m-d')) {
                    $dStr .= ' – ' . $end->format('d M Y');
                }

                $tStr = ($rel->start_time && $rel->end_time)
                    ? substr($rel->start_time, 0, 5) . ' – ' . substr($rel->end_time, 0, 5)
                    : (substr($rel->start_time ?? '', 0, 5) ?: '-');

                return [
                    'id' => $rel->id,
                    'title' => $rel->title,
                    'category' => $rel->category?->name ?? 'Uncategorized',
                    'status' => $st,
                    'date' => $dStr,
                    'time' => $tStr,
                    'location' => $rel->location,
                    'organizer' => $rel->organizer?->name ?? 'Campus Community',
                    'description' => $rel->description,
                    'registration_url' => $rel->registration_url,
                    'image' => $rel->poster ? asset('storage/' . $rel->poster) : null,
                ];
            })
            ->all();

        return view('event.show', compact('event', 'relatedEvents'));
    }
}

