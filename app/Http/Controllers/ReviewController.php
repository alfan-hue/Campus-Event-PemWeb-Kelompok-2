<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Event $event)
    {
        abort_unless($event->status === 'published', 404);

        // Reviews are only allowed once the event has started.
        // Remove these 3 lines if you want to allow reviews on upcoming events.
        $startDate = $event->start_date ?? $event->date;
        abort_if($startDate && now()->startOfDay()->lt($startDate->copy()->startOfDay()), 403,
            'You can only review events that have started.');

        $data = $request->validate([
            'rating'  => ['nullable', 'in:L,D'],
            'content' => ['required', 'string', 'max:2000'],
        ]);

        // An empty "Comment only" radio value arrives as null.
        $event->reviews()->updateOrCreate(
            ['user_id' => $request->user()->id],
            [
                'rating'  => $data['rating'] ?? null,
                'content' => $data['content'],
            ]
        );

        return redirect()
            ->to(url('/events/' . $event->id) . '#reviews')
            ->with('status', 'Your review has been saved.');
    }

    public function destroy(Request $request, Event $event, Review $review)
    {
        abort_unless(
            $review->event_id === $event->id && $review->user_id === $request->user()->id,
            403
        );

        $review->delete();

        return redirect()
            ->to(url('/events/' . $event->id) . '#reviews')
            ->with('status', 'Your review has been deleted.');
    }
}