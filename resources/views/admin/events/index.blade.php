<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Event - Admin</title>
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
        .topbar-title { font-family: 'Syne', sans-serif; font-size: 1.05rem; font-weight: 600; }
        .topbar-badge { background: var(--dark); color: #fff; font-size: .68rem; font-weight: 700; padding: .2rem .55rem; border-radius: 99px; }
        .page { padding: 2rem; flex: 1; }
        .page-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 2rem; opacity: 0; animation: fadeUp .5s ease .05s forwards; }
        .page-header h1 { font-family: 'Syne', sans-serif; font-size: 1.6rem; font-weight: 700; margin-bottom: .25rem; }
        .page-header p { font-size: .875rem; color: var(--mid); }
        .btn-primary { display: inline-flex; align-items: center; gap: .5rem; padding: .7rem 1.25rem; background: var(--dark); color: #fff; border: none; border-radius: .75rem; font-family: 'Mulish', sans-serif; font-size: .875rem; font-weight: 600; text-decoration: none; cursor: pointer; transition: background .15s, transform .1s; }
        .btn-primary:hover { background: #333; transform: translateY(-1px); }
        .table-section { background: var(--surface); border: 1px solid var(--light); border-radius: 1rem; overflow: hidden; opacity: 0; animation: fadeUp .5s ease .15s forwards; }
        table { width: 100%; border-collapse: collapse; }
        th { padding: .75rem 1.5rem; text-align: left; font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: var(--mid); border-bottom: 1px solid var(--light); background: var(--bg); }
        td { padding: .9rem 1.5rem; font-size: .875rem; border-bottom: 1px solid var(--light); vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #fafaf9; }
        .badge { display: inline-flex; align-items: center; padding: .2rem .65rem; border-radius: 99px; font-size: .72rem; font-weight: 600; }
        .badge-upcoming { background: #eff6ff; color: #2563eb; }
        .badge-past { background: #f3f4f6; color: #6b7280; }
        .btn-sm { display: inline-flex; align-items: center; gap: .35rem; padding: .35rem .85rem; border-radius: .5rem; font-size: .78rem; font-weight: 600; text-decoration: none; border: none; cursor: pointer; transition: background .15s; font-family: 'Mulish', sans-serif; }
        .btn-dark { background: var(--dark); color: #fff; }
        .btn-dark:hover { background: #333; }
        .btn-outline { background: transparent; border: 1px solid var(--light); color: var(--dark); }
        .btn-outline:hover { background: var(--bg); }
        .btn-danger { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
        .btn-danger:hover { background: #fee2e2; }
        .empty-state { text-align: center; padding: 4rem 2rem; color: var(--mid); }
        .empty-state svg { margin: 0 auto 1rem; display: block; opacity: .3; }
        .empty-state p { font-size: .9rem; margin-bottom: 1.25rem; }
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
        <span class="topbar-title">Kelola Event</span>
        <span class="topbar-badge">ADMIN</span>
    </div>
    <div class="page">

        <div class="page-header">
            <div>
                <h1>Daftar Event</h1>
                <p>{{ $events->total() }} event terdaftar di sistem</p>
            </div>
            <a href="{{ route('admin.events.create') }}" class="btn-primary">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Buat Event
            </a>
        </div>

        @if(session('success'))
            <div style="margin-bottom:1.25rem;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:.75rem;padding:.9rem 1.25rem;font-size:.875rem;color:#16a34a;">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-section">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Event</th>
                        <th>Tanggal</th>
                        <th>Pendaftar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                    <tr>
                        <td style="color:var(--mid);font-size:.8rem;">{{ $loop->iteration }}</td>
                        <td>
                            <div style="font-weight:600;">{{ $event->name }}</div>
                            <div style="font-size:.78rem;color:var(--mid);margin-top:.2rem;">{{ Str::limit($event->description, 50) }}</div>
                        </td>
                        <td style="color:var(--mid);">{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</td>
                        <td>
                            <span style="font-weight:600;">{{ $event->registrations_count ?? 0 }}</span>
                            <span style="color:var(--mid);font-size:.8rem;"> orang</span>
                        </td>
                        <td>
                            @if(\Carbon\Carbon::parse($event->event_date)->isFuture())
                                <span class="badge badge-upcoming">Akan Datang</span>
                            @else
                                <span class="badge badge-past">Selesai</span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
                                <a href="{{ route('admin.events.participants', $event->id) }}" class="btn-sm btn-dark">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                    Peserta
                                </a>
                                <a href="{{ route('admin.events.edit', $event->id) }}" class="btn-sm btn-outline">Edit</a>
                                <form method="POST" action="{{ route('admin.events.destroy', $event->id) }}" onsubmit="return confirm('Hapus event ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-sm btn-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                <p>Belum ada event yang dibuat.</p>
                                <a href="{{ route('admin.events.create') }}" class="btn-primary">Buat Event Pertama</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($events->hasPages())
        <div style="margin-top:1.25rem;">{{ $events->links() }}</div>
        @endif

    </div>
</div>
</body>
</html>