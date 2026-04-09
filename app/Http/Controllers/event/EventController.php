<?php
// app/Http/Controllers/event/EventController.php

namespace App\Http\Controllers\event;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::withCount('registrations')
            ->orderBy('event_date', 'asc')
            ->paginate(9);
        
        return view('events.index', compact('events'));
    }
    
    public function show(Event $event)
    {
        $event->loadCount('registrations');
        
        return view('events.show', compact('event'));
    }
    
    public function registerForm(Event $event)
    {
        // Cek apakah user sudah terdaftar
        $isRegistered = EventRegistration::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->exists();
        
        if ($isRegistered) {
            return redirect()->route('events.show', $event->id)
                ->with('error', 'Anda sudah terdaftar di event ini.');
        }
        
        // Cek apakah event masih tersedia
        if ($event->event_date < now()) {
            return redirect()->route('events.show', $event->id)
                ->with('error', 'Pendaftaran event ini sudah ditutup.');
        }
        
        return view('events.register', compact('event'));
    }
    
    public function register(Request $request, Event $event)
    {
        // Validasi untuk form biasa (dari halaman register)
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'faculty' => 'required|string|max:255',
        ]);
        
        // Check if already registered
        $existing = EventRegistration::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->exists();
        
        if ($existing) {
            return redirect()->route('events.show', $event->id)
                ->with('error', 'Anda sudah terdaftar di event ini.');
        }
        
        // Check if event is still available
        if ($event->event_date < now()) {
            return redirect()->route('events.show', $event->id)
                ->with('error', 'Pendaftaran event ini sudah ditutup.');
        }
        
        // Create registration dengan data LENGKAP dari form
        $registration = EventRegistration::create([
            'event_id' => $event->id,
            'user_id' => Auth::id(),
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'faculty' => $request->faculty,
            'attended' => false,
            'status' => 'registered',
            'registration_date' => now(),
        ]);
        
        // Notifikasi untuk user
        Notification::create([
            'user_id' => Auth::id(),
            'sender_id' => null,
            'type' => 'event_registration',
            'notifiable_type' => Event::class,
            'notifiable_id' => $event->id,
            'message' => 'Pendaftaran event "' . $event->name . '" berhasil!',
            'action_url' => route('events.show', $event->id),
            'is_read' => false,
        ]);
        
        return redirect()->route('events.show', $event->id)
            ->with('success', 'Pendaftaran berhasil! Data Anda sudah tersimpan.');
    }
}