<?php
// app/Http/Controllers/Admin/AdminUserController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    // Daftar semua user
    public function index(Request $request)
    {
        $query = User::where('role', 'user'); // Hanya user biasa, bukan admin

        // Filter berdasarkan status
        if ($request->status == 'banned') {
            $query->banned();
        } elseif ($request->status == 'active') {
            $query->active();
        }

        // Pencarian
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->latest()->paginate(15);

        $stats = [
            'total' => User::where('role', 'user')->count(),
            'active' => User::where('role', 'user')->active()->count(),
            'banned' => User::where('role', 'user')->banned()->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    // Ban user
    public function ban(Request $request, User $user)
    {
        // Cek jangan sampai admin ban diri sendiri
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat memban diri sendiri.');
        }

        // Cek jangan sampai ban admin lain
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Tidak dapat memban admin lain.');
        }

        $request->validate([
            'ban_reason' => 'required|string|max:500'
        ]);

        $user->ban($request->ban_reason);

        // Buat notifikasi untuk user yang di-ban
        Notification::create([
            'user_id' => $user->id,
            'sender_id' => Auth::id(),
            'type' => 'user_banned',
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'message' => 'Akun Anda telah dibanned. Alasan: ' . $request->ban_reason,
            'action_url' => null,
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'User ' . $user->name . ' berhasil dibanned.');
    }

    // Unban user
    public function unban(User $user)
    {
        $user->unban();

        // Buat notifikasi untuk user yang di-unban
        Notification::create([
            'user_id' => $user->id,
            'sender_id' => Auth::id(),
            'type' => 'user_unbanned',
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'message' => 'Akun Anda telah di-unban. Silakan login kembali.',
            'action_url' => null,
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'User ' . $user->name . ' berhasil di-unban.');
    }

    // Hapus user
    public function destroy(User $user)
    {
        // Cek jangan sampai admin hapus diri sendiri
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus diri sendiri.');
        }

        // Cek jangan sampai hapus admin lain
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Tidak dapat menghapus admin lain.');
        }

        // Hapus semua data terkait user
        $user->posts()->delete();
        $user->comments()->delete();
        $user->likes()->delete();
        $user->eventRegistrations()->delete();
        $user->notifications()->delete();
        $user->connections()->delete();
        
        $user->delete();

        return redirect()->back()->with('success', 'User ' . $user->name . ' berhasil dihapus.');
    }

    // Detail user
    public function show(User $user)
    {
        $user->loadCount(['posts', 'comments', 'eventRegistrations']);
        
        return view('admin.users.show', compact('user'));
    }
}