<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrganizerEventController extends Controller
{
    // GET /organizer/events/create
    public function create()
    {
        $categories = Category::orderBy('name')->pluck('name');

        return view('organizer_dashboard.create', compact('categories'));
    }

    // POST /organizer/events
    // TODO: validate and save to the database later.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:120',
            'category' => 'required|exists:categories,name',
            'description' => 'required|string|max:1000',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'location' => 'required|string|max:150',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'registration_url' => 'required|url',
        ]);

        $category = Category::where('name', $validated['category'])->firstOrFail();

        $poster = null;

        if ($request->hasFile('image')) {
            $poster = $request->file('image')->store('posters', 'public');
        }

        Event::create([
            'organizer_id' => Auth::id(),
            'category_id' => $category->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'date' => $validated['start_date'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'location' => $validated['location'],
            'poster' => $poster,
            'registration_url' => $validated['registration_url'],
            'status' => 'pending',
        ]);

        return redirect()->route('organizer.dashboard')
            ->with('success', 'Event created successfully and waiting for admin approval.');
    }

    // GET /organizer/events/{event}/edit
    public function edit($event)
    {
        $event = Event::where('organizer_id', Auth::id())
            ->findOrFail($event);

        $categories = Category::orderBy('name')->pluck('name');

        return view('organizer_dashboard.edit', compact('event', 'categories'));
    }

    // PUT /organizer/events/{event}
    // TODO: validate and update in the database later.

    public function update(Request $request, $event)
    {
        $event = Event::where('organizer_id', Auth::id())
            ->findOrFail($event);

        $validated = $request->validate([
            'title' => 'required|string|max:120',
            'category' => 'required|exists:categories,name',
            'description' => 'required|string|max:1000',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'location' => 'required|string|max:150',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'registration_url' => 'required|url',
        ]);

        $category = Category::where('name', $validated['category'])
            ->firstOrFail();

        $poster = $event->poster;

        if ($request->hasFile('image')) {
            if ($event->poster) {
                Storage::disk('public')->delete($event->poster);
            }

            $poster = $request->file('image')->store('posters', 'public');
        }

        $event->update([
            'category_id' => $category->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'date' => $validated['start_date'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'location' => $validated['location'],
            'poster' => $poster,
            'registration_url' => $validated['registration_url'],
        ]);

        return redirect()->route('organizer.dashboard')
            ->with('success', 'Event updated successfully.');
    }


    // DELETE /organizer/events/{event}
    // TODO: delete from the database later.

    public function destroy($event)
    {
        $event = Event::where('organizer_id', Auth::id())
            ->findOrFail($event);

        if ($event->poster) {
            Storage::disk('public')->delete($event->poster);
        }

        $event->delete();

        return redirect()->route('organizer.dashboard')
            ->with('success', 'Event deleted successfully.');
    }

}
