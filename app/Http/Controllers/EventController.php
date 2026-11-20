<?php

namespace App\Http\Controllers;

class EventController extends Controller
{
    // GET /events  (view: event.index)
    public function index()
    {
        return view('event.index');
    }

    // GET /events/{event}  (view: event.show)
    public function show($event)
    {
        return view('event.show');
    }
}
