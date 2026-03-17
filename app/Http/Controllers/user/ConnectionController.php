<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ConnectionController extends Controller
{

    // =========================
    // HALAMAN CONNECTION
    // =========================
    public function index()
    {
        $userId = Auth::id();

        // semua user selain diri sendiri
        $users = User::where('id', '!=', $userId)->get();

        // request yang masuk
        $requests = Connection::where('receiver_id', $userId)
            ->where('status', 'pending')
            ->get();

        // connection yang sudah accepted
        $connections = Connection::where(function ($query) use ($userId) {
                $query->where('requester_id', $userId)
                      ->orWhere('receiver_id', $userId);
            })
            ->where('status', 'accepted')
            ->get();

        return view('connections.index', compact(
            'users',
            'requests',
            'connections'
        ));
    }


    // =========================
    // SEND REQUEST
    // =========================
    public function sendRequest($receiverId)
    {
        $userId = Auth::id();

        if ($userId == $receiverId) {
            return redirect()->back();
        }

        // cek reverse request (auto accept)
        $reverse = Connection::where('requester_id', $receiverId)
            ->where('receiver_id', $userId)
            ->where('status', 'pending')
            ->first();

        if ($reverse) {
            $reverse->update([
                'status' => 'accepted'
            ]);

            return redirect()->back()
                ->with('success', 'You are now connected!');
        }

        // cek kalau sudah ada
        $exists = Connection::where(function ($query) use ($userId, $receiverId) {
            $query->where('requester_id', $userId)
                  ->where('receiver_id', $receiverId);
        })->orWhere(function ($query) use ($userId, $receiverId) {
            $query->where('requester_id', $receiverId)
                  ->where('receiver_id', $userId);
        })->first();

        if ($exists) {
            return redirect()->back();
        }

        // buat request baru
        Connection::create([
            'requester_id' => $userId,
            'receiver_id' => $receiverId,
            'status' => 'pending'
        ]);

        return redirect()->back()
            ->with('success', 'Connection request sent!');
    }


    // =========================
    // ACCEPT REQUEST
    // =========================
    public function accept($id)
    {
        $connection = Connection::where('receiver_id', Auth::id())
            ->where('requester_id', $id)
            ->where('status', 'pending')
            ->firstOrFail();

        $connection->update([
            'status' => 'accepted'
        ]);

        return redirect()->back()
            ->with('success', 'Connection accepted!');
    }


    // =========================
    // REMOVE CONNECTION
    // =========================
    public function remove($id)
    {
        Connection::where(function ($query) use ($id) {
            $query->where('requester_id', Auth::id())
                  ->where('receiver_id', $id);
        })->orWhere(function ($query) use ($id) {
            $query->where('requester_id', $id)
                  ->where('receiver_id', Auth::id());
        })->delete();

        return redirect()->back()
            ->with('success', 'Connection removed.');
    }

}