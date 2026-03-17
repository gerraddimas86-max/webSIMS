<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers         = User::count();
        $totalPosts         = Post::count();
        $totalComments      = Comment::count();
        $newUsersThisMonth  = User::whereMonth('created_at', now()->month)->count();
        $newPostsThisWeek   = Post::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();

        // Coba ambil total events jika model Event ada
        $totalEvents   = 0;
        $upcomingEvents = 0;
        if (class_exists(\App\Models\Event::class)) {
            $totalEvents    = \App\Models\Event::count();
            $upcomingEvents = \App\Models\Event::where('event_date', '>=', now())->count();
        }

        // Aktivitas terbaru: gabungkan user & post terbaru
        $recentUsers = User::latest()->take(3)->get()->map(fn($u) => [
            'type'   => 'user',
            'actor'  => $u->name,
            'action' => 'baru saja mendaftar',
            'time'   => $u->created_at->diffForHumans(),
        ]);

        $recentPosts = Post::with('user')->latest()->take(3)->get()->map(fn($p) => [
            'type'   => 'post',
            'actor'  => $p->user->name ?? 'Unknown',
            'action' => 'membuat postingan baru',
            'time'   => $p->created_at->diffForHumans(),
        ]);

        $recentActivities = $recentUsers->merge($recentPosts)
            ->sortByDesc('time')
            ->take(6)
            ->values();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalPosts',
            'totalComments',
            'totalEvents',
            'upcomingEvents',
            'newUsersThisMonth',
            'newPostsThisWeek',
            'recentActivities'
        ));
    }
}