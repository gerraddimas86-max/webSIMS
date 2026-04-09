<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Connection;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil sendiri
     */
    public function index()
    {
        $user = Auth::user();
        
        // Hitung jumlah connections yang ACCEPTED
        $connectionsCount = Connection::where(function ($query) use ($user) {
                $query->where('requester_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })
            ->where('status', 'accepted')
            ->count();
        
        // Hitung jumlah event yang diikuti
        $eventsCount = EventRegistration::where('user_id', $user->id)->count();
        
        // Ambil data connections (6 terbaru)
        $connections = Connection::where(function ($query) use ($user) {
                $query->where('requester_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })
            ->where('status', 'accepted')
            ->with(['requester', 'receiver'])
            ->limit(6)
            ->get()
            ->map(function($connection) use ($user) {
                return $connection->requester_id == $user->id 
                    ? $connection->receiver 
                    : $connection->requester;
            });
        
        // Ambil data events yang diikuti (5 terbaru)
        $events = EventRegistration::where('user_id', $user->id)
            ->with('event')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function($registration) {
                return $registration->event;
            });
        
        // Update counts di user object
        $user->connections_count = $connectionsCount;
        $user->events_count = $eventsCount;
        
        return view('profile.index', compact('user', 'connections', 'events'));
    }
    
    /**
     * Menampilkan halaman edit profil sendiri
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }
    
    /**
     * Memproses update profil sendiri
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'angkatan' => 'nullable|string|max:50',
            'prodi' => 'nullable|string|max:100',
            'bio' => 'nullable|string|max:500',
        ]);
        
        $user->name = $request->name;
        $user->angkatan = $request->angkatan;
        $user->prodi = $request->prodi;
        $user->bio = $request->bio;
        $user->save();
        
        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui!');
    }
    
    /**
     * Menampilkan profil user lain (untuk dilihat)
     */
    public function show($id)
    {
        // Cari user berdasarkan ID
        $user = User::findOrFail($id);
        
        // Jika user yang dilihat adalah diri sendiri, redirect ke halaman profil sendiri
        if ($user->id == Auth::id()) {
            return redirect()->route('profile.index');
        }
        
        // Hitung jumlah connections user tersebut (ACCEPTED)
        $connectionsCount = Connection::where(function ($query) use ($user) {
                $query->where('requester_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })
            ->where('status', 'accepted')
            ->count();
        
        // Hitung jumlah event yang diikuti user tersebut
        $eventsCount = EventRegistration::where('user_id', $user->id)->count();
        
        // Ambil data connections user tersebut (6 terbaru)
        $connections = Connection::where(function ($query) use ($user) {
                $query->where('requester_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })
            ->where('status', 'accepted')
            ->with(['requester', 'receiver'])
            ->limit(6)
            ->get()
            ->map(function($connection) use ($user) {
                return $connection->requester_id == $user->id 
                    ? $connection->receiver 
                    : $connection->requester;
            });
        
        // Cek status koneksi antara user yang login dengan user yang dilihat
        $currentUserId = Auth::id();
        $connection = Connection::where(function ($query) use ($currentUserId, $id) {
                $query->where('requester_id', $currentUserId)
                      ->where('receiver_id', $id);
            })
            ->orWhere(function ($query) use ($currentUserId, $id) {
                $query->where('requester_id', $id)
                      ->where('receiver_id', $currentUserId);
            })
            ->first();
        
        if (!$connection) {
            $connectionStatus = 'not_connected';
        } else {
            $connectionStatus = $connection->status;
        }
        
        // Update counts di user object
        $user->connections_count = $connectionsCount;
        $user->events_count = $eventsCount;
        
        return view('profile.show', compact('user', 'connections', 'connectionStatus'));
    }
    
    /**
     * Get connection status with another user (helper)
     */
    private function getConnectionStatus($userId)
    {
        $currentUserId = Auth::id();
        
        $connection = Connection::where(function ($query) use ($currentUserId, $userId) {
                $query->where('requester_id', $currentUserId)
                      ->where('receiver_id', $userId);
            })
            ->orWhere(function ($query) use ($currentUserId, $userId) {
                $query->where('requester_id', $userId)
                      ->where('receiver_id', $currentUserId);
            })
            ->first();
        
        if (!$connection) {
            return 'not_connected';
        }
        
        return $connection->status;
    }
}