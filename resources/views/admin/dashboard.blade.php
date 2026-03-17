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
            --accent: #141414;
            --danger: #e53e3e;
            --success: #38a169;
            --warning: #d69e2e;
        }

        body {
            font-family: 'Mulish', sans-serif;
            background: var(--bg);
            color: var(--dark);
            display: flex;
            min-height: 100vh;
        }

        /* ── SIDEBAR ─────────────────────────────── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
            transition: transform .3s ease;
        }

        .sidebar-brand {
            padding: 1.75rem 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,.06);
        }
        .brand-label {
            font-size: .65rem;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: rgba(255,255,255,.3);
            margin-bottom: .35rem;
        }
        .brand-name {
            font-family: 'Syne', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: #fff;
        }

        .sidebar-nav {
            padding: 1.25rem .75rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: .2rem;
        }

        .nav-section-label {
            font-size: .62rem;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: rgba(255,255,255,.22);
            padding: .75rem .75rem .3rem;
            margin-top: .5rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .65rem .85rem;
            border-radius: .6rem;
            color: rgba(255,255,255,.5);
            text-decoration: none;
            font-size: .875rem;
            font-weight: 500;
            transition: background .15s, color .15s;
        }
        .nav-item:hover {
            background: rgba(255,255,255,.07);
            color: rgba(255,255,255,.9);
        }
        .nav-item.active {
            background: rgba(255,255,255,.12);
            color: #fff;
        }
        .nav-item svg { flex-shrink: 0; opacity: .7; }
        .nav-item.active svg { opacity: 1; }

        .sidebar-footer {
            padding: 1rem .75rem 1.5rem;
            border-top: 1px solid rgba(255,255,255,.06);
        }
        .admin-chip {
            display: flex;
            align-items: center;
            gap: .65rem;
            padding: .65rem .85rem;
            border-radius: .6rem;
            background: rgba(255,255,255,.06);
        }
        .admin-avatar {
            width: 30px; height: 30px;
            border-radius: 50%;
            background: rgba(255,255,255,.15);
            display: flex; align-items: center; justify-content: center;
            font-size: .75rem; font-weight: 700; color: #fff;
            flex-shrink: 0;
        }
        .admin-info { flex: 1; min-width: 0; }
        .admin-name {
            font-size: .8rem; font-weight: 600; color: #fff;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .admin-role {
            font-size: .68rem; color: rgba(255,255,255,.35);
        }
        .logout-btn {
            background: none; border: none; cursor: pointer;
            color: rgba(255,255,255,.3); padding: .2rem;
            transition: color .15s;
        }
        .logout-btn:hover { color: #ef4444; }

        /* ── MAIN ────────────────────────────────── */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ── TOPBAR ──────────────────────────────── */
        .topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--light);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky; top: 0; z-index: 50;
        }
        .topbar-title {
            font-family: 'Syne', sans-serif;
            font-size: 1.05rem;
            font-weight: 600;
            color: var(--dark);
        }
        .topbar-right {
            display: flex; align-items: center; gap: 1rem;
        }
        .topbar-date {
            font-size: .78rem;
            color: var(--mid);
        }
        .topbar-badge {
            background: var(--dark);
            color: #fff;
            font-size: .68rem;
            font-weight: 700;
            padding: .2rem .55rem;
            border-radius: 99px;
            letter-spacing: .04em;
        }

        /* ── PAGE CONTENT ────────────────────────── */
        .page {
            padding: 2rem;
            flex: 1;
        }

        /* ── PAGE HEADER ─────────────────────────── */
        .page-header {
            margin-bottom: 2rem;
            opacity: 0;
            animation: fadeUp .5s ease .05s forwards;
        }
        .page-header h1 {
            font-family: 'Syne', sans-serif;
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: .25rem;
        }
        .page-header p {
            font-size: .875rem;
            color: var(--mid);
        }

        /* ── STAT CARDS ──────────────────────────── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--light);
            border-radius: 1rem;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
            opacity: 0;
            animation: fadeUp .5s ease forwards;
        }
        .stat-card:nth-child(1) { animation-delay: .1s; }
        .stat-card:nth-child(2) { animation-delay: .18s; }
        .stat-card:nth-child(3) { animation-delay: .26s; }
        .stat-card:nth-child(4) { animation-delay: .34s; }

        .stat-card::after {
            content: '';
            position: absolute;
            bottom: -20px; right: -20px;
            width: 80px; height: 80px;
            border-radius: 50%;
            background: var(--bg);
        }

        .stat-icon {
            width: 38px; height: 38px;
            border-radius: .65rem;
            background: var(--bg);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 1.1rem;
        }
        .stat-value {
            font-family: 'Syne', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
            line-height: 1;
            margin-bottom: .3rem;
        }
        .stat-label {
            font-size: .75rem;
            font-weight: 600;
            color: var(--mid);
            text-transform: uppercase;
            letter-spacing: .06em;
            margin-bottom: .5rem;
        }
        .stat-change {
            font-size: .75rem;
            display: flex; align-items: center; gap: .3rem;
        }
        .stat-change.up { color: var(--success); }
        .stat-change.neutral { color: var(--mid); }

        /* ── BOTTOM GRID ─────────────────────────── */
        .bottom-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
            opacity: 0;
            animation: fadeUp .5s ease .4s forwards;
        }

        .card {
            background: var(--surface);
            border: 1px solid var(--light);
            border-radius: 1rem;
            overflow: hidden;
        }
        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--light);
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-title {
            font-family: 'Syne', sans-serif;
            font-size: .9rem;
            font-weight: 600;
        }
        .card-link {
            font-size: .75rem;
            color: var(--mid);
            text-decoration: none;
            transition: color .15s;
        }
        .card-link:hover { color: var(--dark); }

        /* Activity list */
        .activity-list { padding: .5rem 0; }
        .activity-item {
            display: flex; align-items: flex-start; gap: .85rem;
            padding: .85rem 1.5rem;
            border-bottom: 1px solid var(--light);
            transition: background .15s;
        }
        .activity-item:last-child { border-bottom: none; }
        .activity-item:hover { background: var(--bg); }
        .activity-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            margin-top: 5px;
            flex-shrink: 0;
        }
        .dot-user { background: #3b82f6; }
        .dot-post { background: #8b5cf6; }
        .dot-event { background: #f59e0b; }
        .activity-text {
            flex: 1;
            font-size: .83rem;
            color: var(--dark);
            line-height: 1.45;
        }
        .activity-text span { font-weight: 600; }
        .activity-time {
            font-size: .72rem;
            color: var(--mid);
            white-space: nowrap;
        }

        /* Quick actions */
        .actions-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .75rem;
            padding: 1.25rem 1.5rem;
        }
        .action-btn {
            display: flex; align-items: center; gap: .65rem;
            padding: .85rem 1rem;
            background: var(--bg);
            border: 1px solid var(--light);
            border-radius: .75rem;
            text-decoration: none;
            color: var(--dark);
            font-size: .82rem;
            font-weight: 600;
            transition: background .15s, border-color .15s, transform .1s;
            cursor: pointer;
        }
        .action-btn:hover {
            background: var(--dark);
            color: #fff;
            border-color: var(--dark);
            transform: translateY(-1px);
        }
        .action-btn:hover svg { stroke: #fff; }
        .action-btn svg { transition: stroke .15s; }

        /* ── ANIMATIONS ──────────────────────────── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── RESPONSIVE ──────────────────────────── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main { margin-left: 0; }
            .bottom-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- ══ SIDEBAR ════════════════════════════════════════ -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-label">Panel Kontrol</div>
        <div class="brand-name">Komunitas Pelajar</div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Utama</div>

        <a href="{{ route('admin.dashboard') }}" class="nav-item active">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
            </svg>
            Dashboard
        </a>

        <div class="nav-section-label">Kelola</div>

        <a href="{{ route('admin.users.index') }}" class="nav-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            Manajemen User
        </a>

        <a href="{{ route('admin.events.index') }}" class="nav-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            Manajemen Event
        </a>

        <a href="{{ route('posts.index') }}" class="nav-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/>
                <line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
            </svg>
            Semua Postingan
        </a>

        <div class="nav-section-label">Sistem</div>

        <a href="{{ route('profile.index') }}" class="nav-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
            </svg>
            Profil
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
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- ══ MAIN ═══════════════════════════════════════════ -->
<div class="main">

    <!-- Topbar -->
    <div class="topbar">
        <span class="topbar-title">Dashboard</span>
        <div class="topbar-right">
            <span class="topbar-date">{{ now()->translatedFormat('l, d F Y') }}</span>
            <span class="topbar-badge">ADMIN</span>
        </div>
    </div>

    <!-- Page Content -->
    <div class="page">

        <!-- Header -->
        <div class="page-header">
            <h1>Selamat datang, {{ Auth::user()->name }} 👋</h1>
            <p>Berikut ringkasan aktivitas komunitas hari ini.</p>
        </div>

        <!-- Stat Cards -->
        <div class="stats-grid">

            <div class="stat-card">
                <div class="stat-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <div class="stat-value">{{ number_format($totalUsers ?? 0) }}</div>
                <div class="stat-label">Total User</div>
                <div class="stat-change up">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"/></svg>
                    +{{ $newUsersThisMonth ?? 0 }} bulan ini
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                </div>
                <div class="stat-value">{{ number_format($totalPosts ?? 0) }}</div>
                <div class="stat-label">Total Postingan</div>
                <div class="stat-change up">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"/></svg>
                    +{{ $newPostsThisWeek ?? 0 }} minggu ini
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </div>
                <div class="stat-value">{{ number_format($totalEvents ?? 0) }}</div>
                <div class="stat-label">Total Event</div>
                <div class="stat-change neutral">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    {{ $upcomingEvents ?? 0 }} akan datang
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                </div>
                <div class="stat-value">{{ number_format($totalComments ?? 0) }}</div>
                <div class="stat-label">Total Komentar</div>
                <div class="stat-change up">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"/></svg>
                    Aktif hari ini
                </div>
            </div>

        </div>

        <!-- Bottom Grid -->
        <div class="bottom-grid">

            <!-- Recent Activity -->
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Aktivitas Terbaru</span>
                    <a href="{{ route('posts.index') }}" class="card-link">Lihat semua →</a>
                </div>
                <div class="activity-list">
                    @forelse($recentActivities ?? [] as $activity)
                        <div class="activity-item">
                            <div class="activity-dot dot-{{ $activity['type'] }}"></div>
                            <div class="activity-text">
                                <span>{{ $activity['actor'] }}</span> {{ $activity['action'] }}
                            </div>
                            <div class="activity-time">{{ $activity['time'] }}</div>
                        </div>
                    @empty
                        <div class="activity-item">
                            <div class="activity-dot dot-user"></div>
                            <div class="activity-text">Belum ada aktivitas terbaru.</div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Aksi Cepat</span>
                </div>
                <div class="actions-grid">
                    <a href="{{ route('admin.users.index') }}" class="action-btn">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#6b6b6b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                        </svg>
                        Kelola User
                    </a>
                    <a href="{{ route('admin.events.create') }}" class="action-btn">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#6b6b6b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/>
                        </svg>
                        Buat Event
                    </a>
                    <a href="{{ route('admin.events.index') }}" class="action-btn">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#6b6b6b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        Kelola Event
                    </a>
                    <a href="{{ route('posts.index') }}" class="action-btn">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#6b6b6b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                        </svg>
                        Lihat Post
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>