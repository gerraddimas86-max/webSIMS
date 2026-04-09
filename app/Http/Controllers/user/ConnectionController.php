<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Connection;
use App\Models\User;
use App\Models\Message;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConnectionController extends Controller
{

    // =========================
    // HALAMAN CONNECTION
    // =========================
    public function index()
    {
        $userId = Auth::id();

        // HANYA user dengan role 'user' yang bisa dilihat (admin tidak muncul)
        $users = User::where('id', '!=', $userId)
            ->where('role', 'user')
            ->get();

        // request yang masuk (dari user biasa)
        $requests = Connection::where('receiver_id', $userId)
            ->where('status', 'pending')
            ->with('requester')
            ->get();

        // connection yang sudah accepted
        $connections = Connection::where(function ($query) use ($userId) {
                $query->where('requester_id', $userId)
                      ->orWhere('receiver_id', $userId);
            })
            ->where('status', 'accepted')
            ->with(['requester', 'receiver'])
            ->get();

        // Tambahkan status koneksi untuk setiap user
        $users = $users->map(function($user) use ($userId) {
            $connection = Connection::where(function($query) use ($userId, $user) {
                $query->where('requester_id', $userId)
                      ->where('receiver_id', $user->id);
            })->orWhere(function($query) use ($userId, $user) {
                $query->where('requester_id', $user->id)
                      ->where('receiver_id', $userId);
            })->first();
            
            if ($connection) {
                $user->connection_status = $connection->status;
                $user->connection_id = $connection->id;
            } else {
                $user->connection_status = 'not_connected';
            }
            
            return $user;
        });

        return view('connections.index', compact(
            'users',
            'requests',
            'connections'
        ));
    }


    // =========================
    // SEND REQUEST (via redirect)
    // =========================
    public function sendRequest($receiverId)
    {
        $userId = Auth::id();

        if ($userId == $receiverId) {
            return redirect()->back()->with('error', 'Tidak bisa terhubung dengan diri sendiri');
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
            if ($exists->status == 'pending') {
                return redirect()->back()->with('info', 'Request sudah terkirim');
            } elseif ($exists->status == 'accepted') {
                return redirect()->back()->with('info', 'Sudah terhubung');
            }
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
    // SEND REQUEST VIA AJAX (untuk tombol Connect di view)
    // =========================
    public function sendConnectionRequest(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);
        
        $receiverId = $request->user_id;
        $userId = Auth::id();

        if ($userId == $receiverId) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa terhubung dengan diri sendiri'
            ], 400);
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

            return response()->json([
                'success' => true,
                'message' => 'You are now connected!',
                'status' => 'connected'
            ]);
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
            $message = '';
            $status = $exists->status;
            
            if ($status == 'pending') {
                $message = 'Request sudah terkirim dan menunggu konfirmasi';
            } elseif ($status == 'accepted') {
                $message = 'Anda sudah terhubung dengan user ini';
            } elseif ($status == 'rejected') {
                $message = 'Request koneksi ditolak';
            }
            
            return response()->json([
                'success' => false,
                'message' => $message,
                'status' => $status
            ], 400);
        }

        // buat request baru
        $connection = Connection::create([
            'requester_id' => $userId,
            'receiver_id' => $receiverId,
            'status' => 'pending'
        ]);

        // ==================== CREATE NOTIFICATION ====================
        Notification::create([
            'user_id' => $receiverId,
            'sender_id' => $userId,
            'type' => 'connection_request',
            'notifiable_type' => Connection::class,
            'notifiable_id' => $connection->id,
            'message' => Auth::user()->name . ' ingin terhubung denganmu',
            'action_url' => route('connections.index'),
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Connection request sent!',
            'status' => 'pending',
            'data' => $connection
        ]);
    }


    // =========================
    // ACCEPT REQUEST (via redirect)
    // =========================
    public function accept($id)
    {
        $connection = Connection::where('receiver_id', Auth::id())
            ->where('requester_id', $id)
            ->where('status', 'pending')
            ->first();

        if (!$connection) {
            return redirect()->back()->with('error', 'Request tidak ditemukan');
        }

        $connection->update([
            'status' => 'accepted'
        ]);

        // ==================== CREATE NOTIFICATION ====================
        Notification::create([
            'user_id' => $id, // pengirim request
            'sender_id' => Auth::id(),
            'type' => 'connection_accepted',
            'notifiable_type' => Connection::class,
            'notifiable_id' => $connection->id,
            'message' => Auth::user()->name . ' menerima permintaan koneksimu',
            'action_url' => route('connections.chat', Auth::id()),
            'is_read' => false,
        ]);

        return redirect()->back()
            ->with('success', 'Connection accepted!');
    }


    // =========================
    // ACCEPT REQUEST VIA AJAX (DIPERBAIKI)
    // =========================
    public function acceptRequest($id)
    {
        try {
            $connection = Connection::where('receiver_id', Auth::id())
                ->where('requester_id', $id)
                ->where('status', 'pending')
                ->first();

            if (!$connection) {
                return response()->json([
                    'success' => false,
                    'message' => 'Request tidak ditemukan'
                ], 404);
            }

            $connection->update([
                'status' => 'accepted'
            ]);

            // ==================== CREATE NOTIFICATION ====================
            Notification::create([
                'user_id' => $id, // pengirim request
                'sender_id' => Auth::id(),
                'type' => 'connection_accepted',
                'notifiable_type' => Connection::class,
                'notifiable_id' => $connection->id,
                'message' => Auth::user()->name . ' menerima permintaan koneksimu',
                'action_url' => route('connections.chat', Auth::id()),
                'is_read' => false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Koneksi berhasil diterima!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }


    // =========================
    // REJECT REQUEST VIA AJAX (DIPERBAIKI)
    // =========================
    public function rejectRequest($id)
    {
        try {
            $connection = Connection::where('receiver_id', Auth::id())
                ->where('requester_id', $id)
                ->where('status', 'pending')
                ->first();

            if (!$connection) {
                return response()->json([
                    'success' => false,
                    'message' => 'Request tidak ditemukan'
                ], 404);
            }

            $connection->delete();

            return response()->json([
                'success' => true,
                'message' => 'Request ditolak!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }


    // =========================
    // REMOVE CONNECTION (via redirect)
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


    // =========================
    // REMOVE CONNECTION VIA AJAX (DIPERBAIKI)
    // =========================
    public function removeConnection($id)
    {
        try {
            $deleted = Connection::where(function ($query) use ($id) {
                $query->where('requester_id', Auth::id())
                      ->where('receiver_id', $id);
            })->orWhere(function ($query) use ($id) {
                $query->where('requester_id', $id)
                      ->where('receiver_id', Auth::id());
            })->delete();

            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Koneksi tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Koneksi berhasil diputus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }


    // =========================
    // CHAT PAGE
    // =========================
    public function chat($id)
    {
        $receiver = User::findOrFail($id);
        $userId = Auth::id();
        
        // Cek apakah sudah terhubung
        $isConnected = Connection::where(function($query) use ($userId, $id) {
            $query->where('requester_id', $userId)
                  ->where('receiver_id', $id)
                  ->where('status', 'accepted');
        })->orWhere(function($query) use ($userId, $id) {
            $query->where('requester_id', $id)
                  ->where('receiver_id', $userId)
                  ->where('status', 'accepted');
        })->exists();
        
        if (!$isConnected) {
            return redirect()->route('connections.index')
                ->with('error', 'Anda harus terhubung terlebih dahulu untuk chat');
        }
        
        // Ambil pesan antara dua user
        $messages = Message::where(function($query) use ($userId, $id) {
            $query->where('sender_id', $userId)
                  ->where('receiver_id', $id);
        })->orWhere(function($query) use ($userId, $id) {
            $query->where('sender_id', $id)
                  ->where('receiver_id', $userId);
        })->orderBy('created_at', 'asc')->get();
        
        return view('connections.chat', compact('receiver', 'messages'));
    }


    // =========================
    // SEND MESSAGE VIA AJAX
    // =========================
    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);
        
        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $id,
            'message' => $request->message
        ]);
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'sent_at' => now()->setTimezone('Asia/Jakarta')->format('H:i')
        ]);
    }

    // =========================
    // UPDATE MESSAGE VIA AJAX
    // =========================
    public function updateMessage(Request $request, $id)
    {
        try {
            $message = Message::findOrFail($id);
            
            if ($message->sender_id !== Auth::id()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            
            $request->validate(['message' => 'required|string|max:1000']);
            
            $message->update(['message' => $request->message]);
            
            return response()->json(['success' => true, 'message' => 'Pesan berhasil diperbarui']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Pesan tidak ditemukan'], 404);
        }
    }

    // =========================
    // DELETE MESSAGE VIA AJAX
    // =========================
    public function deleteMessage($id)
    {
        try {
            $message = Message::findOrFail($id);
            
            if ($message->sender_id !== Auth::id()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            
            $message->delete();
            
            return response()->json(['success' => true, 'message' => 'Pesan berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Pesan tidak ditemukan'], 404);
        }
    }
    
}