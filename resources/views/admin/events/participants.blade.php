<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peserta Event - Admin</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700&family=Mulish:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root { --sidebar-w: 240px; --bg: #f5f4f1; --surface: #ffffff; --dark: #141414; --mid: #6b6b6b; --light: #e8e6e0; }
        body { font-family: 'Mulish', sans-serif; background: var(--bg); color: var(--dark); display: flex; min-height: 100vh; }
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
        .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; }
        .topbar { background: var(--surface); border-bottom: 1px solid var(--light); padding: 1rem 2rem; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 50; }
        .topbar-left { display: flex; align-items: center; gap: .75rem; }
        .topbar-back { display: flex; align-items: center; gap: .4rem; font-size: .82rem; color: var(--mid); text-decoration: none; transition: color .15s; }
        .topbar-back:hover { color: var(--dark); }
        .topbar-title { font-family: 'Syne', sans-serif; font-size: 1.05rem; font-weight: 600; }
        .topbar-badge { background: var(--dark); color: #fff; font-size: .68rem; font-weight: 700; padding: .2rem .55rem; border-radius: 99px; }
        .page { padding: 2rem; flex: 1; }

        /* EVENT INFO CARD */
        .event-info { background: var(--dark); color: #fff; border-radius: 1rem; padding: 1.5rem 2rem; margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; opacity: 0; animation: fadeUp .5s ease .05s forwards; }
        .event-info-left h2 { font-family: 'Syne', sans-serif; font-size: 1.25rem; font-weight: 700; margin-bottom: .35rem; }
        .event-info-left p { font-size: .85rem; color: rgba(255,255,255,.55); }
        .event-stats { display: flex; gap: 2rem; }
        .event-stat { text-align: center; }
        .event-stat-num { font-family: 'Syne', sans-serif; font-size: 1.6rem; font-weight: 700; line-height: 1; }
        .event-stat-label { font-size: .7rem; color: rgba(255,255,255,.4); text-transform: uppercase; letter-spacing: .07em; margin-top: .25rem; }

        /* TABLE */
        .table-section { background: var(--surface); border: 1px solid var(--light); border-radius: 1rem; overflow: hidden; opacity: 0; animation: fadeUp .5s ease .15s forwards; }
        .table-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--light); display: flex; align-items: center; justify-content: space-between; }
        .table-header-title { font-family: 'Syne', sans-serif; font-size: .9rem; font-weight: 600; }
        .search-input { padding: .5rem 1rem; background: var(--bg); border: 1.5px solid var(--light); border-radius: .65rem; font-family: 'Mulish', sans-serif; font-size: .85rem; outline: none; transition: border-color .2s; width: 220px; }
        .search-input:focus { border-color: var(--dark); }
        table { width: 100%; border-collapse: collapse; }
        th { padding: .75rem 1.5rem; text-align: left; font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: var(--mid); border-bottom: 1px solid var(--light); background: var(--bg); }
        td { padding: .9rem 1.5rem; font-size: .875rem; border-bottom: 1px solid var(--light); vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #fafaf9; }
        .user-cell { display: flex; align-items: center; gap: .75rem; }
        .user-avatar { width: 34px; height: 34px; border-radius: 50%; background: var(--dark); color: #fff; display: flex; align-items: center; justify-content: center; font-size: .8rem; font-weight: 700; flex-shrink: 0; }
        .user-name { font-weight: 600; font-size: .875rem; }
        .user-email { font-size: .75rem; color: var(--mid); margin-top: .1rem; }
        .badge { display: inline-flex; align-items: center; padding: .2rem .65rem; border-radius: 99px; font-size: .72rem; font-weight: 600; }
        .badge-attended { background: #f0fdf4; color: #16a34a; }
        .badge-registered { background: #eff6ff; color: #2563eb; }
        .empty-state { text-align: center; padding: 4rem 2rem; color: var(--mid); }
        .empty-state svg { margin: 0 auto 1rem; display: block; opacity: .3; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-label">Panel Kontrol</div>
        <div class="brand-name">Komunitas Pelajar</div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section-label">Menu</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            Dashboard
        </a>
        <a href="{{ route('admin.events.index') }}" class="nav-item active">
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

<div class="main">
    <div class="topbar">
        <div class="topbar-left">
            <a href="{{ route('admin.events.index') }}" class="topbar-back">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                Semua Event
            </a>
            <span style="color:var(--light)">/</span>
            <span class="topbar-title">Peserta</span>
        </div>
        <span class="topbar-badge">ADMIN</span>
    </div>

    <div class="page">

        <!-- EVENT INFO -->
        <div class="event-info">
            <div class="event-info-left">
                <h2>{{ $event->name }}</h2>
                <p>
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:.3rem;"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    {{ \Carbon\Carbon::parse($event->event_date)->format('d F Y') }}
                </p>
            </div>
            <div class="event-stats">
                <div class="event-stat">
                    <div class="event-stat-num">{{ $registrations->total() }}</div>
                    <div class="event-stat-label">Pendaftar</div>
                </div>
                <div class="event-stat">
                    <div class="event-stat-num">{{ $registrations->where('attended', true)->count() }}</div>
                    <div class="event-stat-label">Hadir</div>
                </div>
            </div>
        </div>

        <!-- PARTICIPANTS TABLE -->
        <div class="table-section">
            <div class="table-header">
                <span class="table-header-title">Daftar Peserta</span>
                <input type="text" class="search-input" placeholder="Cari nama peserta..." id="searchInput" oninput="filterTable()">
            </div>
            <table id="participantsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Peserta</th>
                        <th>Tanggal Daftar</th>
                        <th>Status Kehadiran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registrations as $reg)
                    <tr>
                        <td style="color:var(--mid);font-size:.8rem;">{{ $loop->iteration }}</td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar">{{ substr($reg->user->name ?? 'U', 0, 1) }}</div>
                                <div>
                                    <div class="user-name">{{ $reg->user->name ?? 'Unknown' }}</div>
                                    <div class="user-email">{{ $reg->user->email ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="color:var(--mid);">{{ $reg->created_at->format('d M Y, H:i') }}</td>
                        <td>
                            @if($reg->attended)
                                <span class="badge badge-attended">✓ Hadir</span>
                            @else
                                <span class="badge badge-registered">Terdaftar</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="empty-state">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                <p>Belum ada peserta yang mendaftar.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($registrations->hasPages())
        <div style="margin-top:1.25rem;">{{ $registrations->links() }}</div>
        @endif

    </div>
</div>

<script>
function filterTable() {
    const input = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#participantsTable tbody tr');
    rows.forEach(row => {
        const name = row.querySelector('.user-name')?.textContent.toLowerCase() ?? '';
        const email = row.querySelector('.user-email')?.textContent.toLowerCase() ?? '';
        row.style.display = (name.includes(input) || email.includes(input)) ? '' : 'none';
    });
}
</script>
</body>
</html>