<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Komunitas Pelajar</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700&family=Mulish:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --sidebar-w: 240px;
            --bg: #f5f4f1;
            --surface: #ffffff;
            --dark: #141414;
            --mid: #6b6b6b;
            --light: #e8e6e0;
        }
        body { font-family: 'Mulish', sans-serif; background: var(--bg); color: var(--dark); display: flex; min-height: 100vh; }

        /* SIDEBAR */
        .sidebar { width: var(--sidebar-w); background: var(--dark); min-height: 100vh; display: flex; flex-direction: column; position: fixed; top: 0; left: 0; z-index: 100; }
        .sidebar-brand { padding: 1.75rem 1.5rem 1.25rem; border-bottom: 1px solid rgba(255,255,255,.06); }
        .brand-label { font-size: .65rem; letter-spacing: .14em; text-transform: uppercase; color: rgba(255,255,255,.3); margin-bottom: .35rem; }
        .brand-name { font-family: 'Syne', sans-serif; font-size: 1.1rem; font-weight: 700; color: #fff; }
        .sidebar-nav { padding: 1.25rem .75rem; flex: 1; display: flex; flex-direction: column; gap: .2rem; }
        .nav-section-label { font-size: .62rem; letter-spacing: .12em; text-transform: uppercase; color: rgba(255,255,255,.22); padding: .75rem .75rem .3rem; margin-top: .5rem; }
        .nav-item { display: flex; align-items: center; gap: .75rem; padding: .65rem .85rem; border-radius: .6rem; color: rgba(255,255,255,.5); text-decoration: none; font-size: .875rem; font-weight: 500; transition: background .15s, color .15s; }
        .nav-item:hover { background: rgba(255,255,255,.07); color: rgba(255,255,255,.9); }
        .nav-item.active { background: rgba(255,255,255,.12); color: #fff; }
        .sidebar-footer { padding: 1rem .75rem 1.5rem; border-top: 1px solid rgba(255,255,255,.06); }
        .admin-chip { display: flex; align-items: center; gap: .65rem; padding: .65rem .85rem; border-radius: .6rem; background: rgba(255,255,255,.06); }
        .admin-avatar { width: 30px; height: 30px; border-radius: 50%; background: rgba(255,255,255,.15); display: flex; align-items: center; justify-content: center; font-size: .75rem; font-weight: 700; color: #fff; flex-shrink: 0; }
        .admin-info { flex: 1; min-width: 0; }
        .admin-name { font-size: .8rem; font-weight: 600; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .admin-role { font-size: .68rem; color: rgba(255,255,255,.35); }
        .logout-btn { background: none; border: none; cursor: pointer; color: rgba(255,255,255,.3); padding: .2rem; transition: color .15s; }
        .logout-btn:hover { color: #ef4444; }

        /* MAIN */
        .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; }
        .topbar { background: var(--surface); border-bottom: 1px solid var(--light); padding: 1rem 2rem; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 50; }
        .topbar-title { font-family: 'Syne', sans-serif; font-size: 1.05rem; font-weight: 600; }
        .topbar-date { font-size: .78rem; color: var(--mid); }
        .topbar-badge { background: var(--dark); color: #fff; font-size: .68rem; font-weight: 700; padding: .2rem .55rem; border-radius: 99px; letter-spacing: .04em; }
        .page { padding: 2rem; flex: 1; }

        /* PAGE HEADER */
        .page-header { margin-bottom: 2rem; opacity: 0; animation: fadeUp .5s ease .05s forwards; }
        .page-header h1 { font-family: 'Syne', sans-serif; font-size: 1.6rem; font-weight: 700; margin-bottom: .25rem; }
        .page-header p { font-size: .875rem; color: var(--mid); }

        /* STAT CARDS */
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem; margin-bottom: 2rem; }
        .stat-card { background: var(--surface); border: 1px solid var(--light); border-radius: 1rem; padding: 1.5rem; opacity: 0; animation: fadeUp .5s ease forwards; }
        .stat-card:nth-child(1) { animation-delay: .1s; }
        .stat-card:nth-child(2) { animation-delay: .18s; }
        .stat-card:nth-child(3) { animation-delay: .26s; }
        .stat-icon { width: 38px; height: 38px; border-radius: .65rem; background: var(--bg); display: flex; align-items: center; justify-content: center; margin-bottom: 1.1rem; }
        .stat-value { font-family: 'Syne', sans-serif; font-size: 2rem; font-weight: 700; line-height: 1; margin-bottom: .3rem; }
        .stat-label { font-size: .75rem; font-weight: 600; color: var(--mid); text-transform: uppercase; letter-spacing: .06em; margin-bottom: .5rem; }
        .stat-sub { font-size: .75rem; color: var(--mid); }

        /* QUICK ACTIONS */
        .actions-section { opacity: 0; animation: fadeUp .5s ease .35s forwards; margin-bottom: 2rem; }
        .section-title { font-family: 'Syne', sans-serif; font-size: .95rem; font-weight: 600; margin-bottom: 1rem; }
        .actions-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }
        .action-card { background: var(--surface); border: 1px solid var(--light); border-radius: 1rem; padding: 1.25rem 1.5rem; text-decoration: none; color: var(--dark); display: flex; align-items: center; gap: 1rem; transition: border-color .15s, transform .15s, box-shadow .15s; }
        .action-card:hover { border-color: var(--dark); transform: translateY(-2px); box-shadow: 0 4px 16px rgba(0,0,0,.08); }
        .action-icon { width: 42px; height: 42px; border-radius: .75rem; background: var(--bg); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .action-text strong { display: block; font-size: .9rem; font-weight: 600; margin-bottom: .15rem; }
        .action-text span { font-size: .78rem; color: var(--mid); }

        /* RECENT EVENTS TABLE */
        .table-section { background: var(--surface); border: 1px solid var(--light); border-radius: 1rem; overflow: hidden; opacity: 0; animation: fadeUp .5s ease .45s forwards; }
        .table-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--light); display: flex; align-items: center; justify-content: space-between; }
        .table-header-title { font-family: 'Syne', sans-serif; font-size: .9rem; font-weight: 600; }
        .table-link { font-size: .78rem; color: var(--mid); text-decoration: none; transition: color .15s; }
        .table-link:hover { color: var(--dark); }
        table { width: 100%; border-collapse: collapse; }
        th { padding: .75rem 1.5rem; text-align: left; font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: var(--mid); border-bottom: 1px solid var(--light); background: var(--bg); }
        td { padding: .9rem 1.5rem; font-size: .85rem; border-bottom: 1px solid var(--light); }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #fafaf9; }
        .badge { display: inline-flex; align-items: center; padding: .2rem .65rem; border-radius: 99px; font-size: .72rem; font-weight: 600; }
        .badge-upcoming { background: #eff6ff; color: #2563eb; }
        .badge-past { background: #f3f4f6; color: #6b7280; }
        .btn-sm { display: inline-flex; align-items: center; gap: .35rem; padding: .35rem .85rem; border-radius: .5rem; font-size: .78rem; font-weight: 600; text-decoration: none; transition: background .15s; }
        .btn-dark { background: var(--dark); color: #fff; }
        .btn-dark:hover { background: #333; }
        .btn-outline { background: transparent; border: 1px solid var(--light); color: var(--dark); }
        .btn-outline:hover { background: var(--bg); }

        @keyframes fadeUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-label">Panel Kontrol</div>
        <div class="brand-name">Komunitas Pelajar</div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section-label">Menu</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-item active">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            Dashboard
        </a>
        <a href="{{ route('admin.events.index') }}" class="nav-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            Kelola Event
        </a>
        <a href="{{ route('admin.events.create') }}" class="nav-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
            Buat Event
        </a>
    </nav>
    <div class="sidebar-footer">
        <div class="admin-chip">
            <div class="admin-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
            <div class="admin-info">
                <div class="admin-name">{{ Auth::user()->name }}</div>
                <div class="admin-role">Administrator</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn" title="Logout">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- MAIN -->
<div class="main">
    <div class="topbar">
        <span class="topbar-title">Dashboard</span>
        <div style="display:flex;align-items:center;gap:1rem;">
            <span class="topbar-date">{{ now()->translatedFormat('l, d F Y') }}</span>
            <span class="topbar-badge">ADMIN</span>
        </div>
    </div>

    <div class="page">

        <div class="page-header">
            <h1>Selamat datang, {{ Auth::user()->name }} 👋</h1>
            <p>Kelola event dan pantau pendaftaran komunitas dari sini.</p>
        </div>

        <!-- STAT CARDS -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
                <div class="stat-value">{{ number_format($totalEvents) }}</div>
                <div class="stat-label">Total Event</div>
                <div class="stat-sub">{{ $upcomingEvents }} akan datang</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <div class="stat-value">{{ number_format($totalUsers) }}</div>
                <div class="stat-label">Total User</div>
                <div class="stat-sub">+{{ $newUsersThisMonth }} bulan ini</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                </div>
                <div class="stat-value">{{ number_format($totalRegistrations) }}</div>
                <div class="stat-label">Total Pendaftar</div>
                <div class="stat-sub">Dari semua event</div>
            </div>
        </div>

        <!-- QUICK ACTIONS -->
        <div class="actions-section">
            <div class="section-title">Aksi Cepat</div>
            <div class="actions-grid">
                <a href="{{ route('admin.events.create') }}" class="action-card">
                    <div class="action-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                    </div>
                    <div class="action-text">
                        <strong>Buat Event Baru</strong>
                        <span>Tambahkan event untuk komunitas</span>
                    </div>
                </a>
                <a href="{{ route('admin.events.index') }}" class="action-card">
                    <div class="action-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </div>
                    <div class="action-text">
                        <strong>Lihat Semua Event</strong>
                        <span>Kelola & pantau event aktif</span>
                    </div>
                </a>
                <a href="{{ route('admin.events.index') }}#registrations" class="action-card">
                    <div class="action-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    </div>
                    <div class="action-text">
                        <strong>Data Peserta</strong>
                        <span>Lihat pendaftar per event</span>
                    </div>
                </a>
            </div>
        </div>

        <!-- RECENT EVENTS TABLE -->
        <div class="table-section">
            <div class="table-header">
                <span class="table-header-title">Event Terbaru</span>
                <a href="{{ route('admin.events.index') }}" class="table-link">Lihat semua →</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Nama Event</th>
                        <th>Tanggal</th>
                        <th>Pendaftar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentEvents as $event)
                    <tr>
                        <td style="font-weight:600;">{{ $event->name }}</td>
                        <td style="color:var(--mid);">{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</td>
                        <td>{{ $event->registrations_count ?? 0 }} orang</td>
                        <td>
                            @if(\Carbon\Carbon::parse($event->event_date)->isFuture())
                                <span class="badge badge-upcoming">Akan Datang</span>
                            @else
                                <span class="badge badge-past">Selesai</span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex;gap:.5rem;">
                                <a href="{{ route('admin.events.participants', $event->id) }}" class="btn-sm btn-dark">Peserta</a>
                                <a href="{{ route('admin.events.edit', $event->id) }}" class="btn-sm btn-outline">Edit</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;color:var(--mid);padding:2rem;">Belum ada event.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

</body>
</html>