<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('user')
            ->where('is_active', true)
            ->where('end_date', '>', now())
            ->orderBy('start_date', 'asc')
            ->get();

        return view('events.index', compact('events'));
    }

    public function show(Event $event)
    {
        $event->load('user');

        return view('events.show', compact('event'));
    }
}
