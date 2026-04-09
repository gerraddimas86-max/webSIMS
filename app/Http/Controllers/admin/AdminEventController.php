<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

        $event = Event::create($request->only('name', 'description', 'event_date'));

        // ==================== NOTIFIKASI EVENT BARU UNTUK SEMUA USER ====================
        $users = User::where('role', 'user')->get();
        
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'sender_id' => Auth::id(),
                'type' => 'new_event',
                'notifiable_type' => Event::class,
                'notifiable_id' => $event->id,
                'message' => 'Event baru: "' . $event->name . '" - ' . Carbon::parse($event->event_date)->translatedFormat('d F Y'),
                'action_url' => route('events.show', $event->id),
                'is_read' => false,
            ]);
        }

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil dibuat! Notifikasi telah dikirim ke semua user.');
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
        // Ambil semua data registrasi dengan relasi user (opsional)
        $registrations = EventRegistration::where('event_id', $event->id)
            ->latest('registration_date')
            ->paginate(20);

        return view('admin.events.participants', compact('event', 'registrations'));
    }
}