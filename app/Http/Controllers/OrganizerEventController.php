<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrganizerEventController extends Controller
{
    // GET /organizer/events/create
    public function create()
    {
        return view('organizer_dashboard.create');
    }

    // POST /organizer/events
    // TODO: validate and save to the database later.
    public function store(Request $request)
    {
        return redirect()->route('organizer.dashboard')
            ->with('success', 'Preview only: nothing was saved yet.');
    }

    // GET /organizer/events/{event}/edit
    public function edit($event)
    {
        return view('organizer_dashboard.edit');
    }

    // PUT /organizer/events/{event}
    // TODO: validate and update in the database later.
    public function update(Request $request, $event)
    {
        return redirect()->route('organizer.dashboard')
            ->with('success', 'Preview only: nothing was saved yet.');
    }

    // DELETE /organizer/events/{event}
    // TODO: delete from the database later.
    public function destroy($event)
    {
        return redirect()->route('organizer.dashboard')
            ->with('success', 'Preview only: nothing was deleted yet.');
    }
}
