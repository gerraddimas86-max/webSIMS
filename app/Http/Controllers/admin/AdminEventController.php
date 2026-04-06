<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;

class AdminEventController extends Controller
{
    public function index()
    {
        $events = Event::withCount('registrations')->latest()->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'event_date'  => 'required|date|after_or_equal:today',
        ]);

        Event::create($request->only('name', 'description', 'event_date'));

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil dibuat!');
    }

    public function edit(Event $event)
    {
        return view('admin.events.create', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'event_date'  => 'required|date',
        ]);

        $event->update($request->only('name', 'description', 'event_date'));

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil diperbarui!');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil dihapus.');
    }

    public function participants(Event $event)
    {
        $registrations = EventRegistration::with('user')
            ->where('event_id', $event->id)
            ->latest()
            ->paginate(20);

        return view('admin.events.participants', compact('event', 'registrations'));
    }
}