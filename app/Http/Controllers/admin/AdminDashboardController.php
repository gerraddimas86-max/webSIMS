<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Event;
use App\Models\EventRegistration;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalEvents        = Event::count();
        $upcomingEvents     = Event::where('event_date', '>=', now())->count();
        $totalUsers         = User::where('role', 'user')->count();
        $newUsersThisMonth  = User::where('role', 'user')->whereMonth('created_at', now()->month)->count();
        $totalRegistrations = EventRegistration::count();

        $recentEvents = Event::withCount('registrations')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalEvents',
            'upcomingEvents',
            'totalUsers',
            'newUsersThisMonth',
            'totalRegistrations',
            'recentEvents'
        ));
    }
}