<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use Illuminate\Support\Facades\Auth;

class ConnectionController extends Controller
{
    public function sendRequest($receiverId)
    {
        $userId = Auth::id();

        if ($userId == $receiverId) {
            return redirect()->back();
        }

        // Cek reverse request (auto accept)
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

        // Cek kalau sudah ada
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

        // Buat request baru
        Connection::create([
            'requester_id' => $userId,
            'receiver_id' => $receiverId,
            'status' => 'pending'
        ]);

        return redirect()->back()
            ->with('success', 'Connection request sent!');
    }

    public function accept($id)
    {
        $connection = Connection::where('receiver_id', Auth::id())
            ->where('requester_id', $id)
            ->where('status', 'pending')
            ->firstOrFail();

        $connection->update([
            'status' => 'accepted'
        ]);

        return redirect()->back();
    }

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