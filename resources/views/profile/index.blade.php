@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; }
.profile-wrap {
  font-family: 'Plus Jakarta Sans', sans-serif;
  background: #f0f4f8;
  min-height: calc(100vh - 64px);
  display: flex;
  flex-direction: column;
  align-items: center;
}

/* BANNER full width */
.bn {
  width: 100%;
  height: 240px;
  position: relative;
  overflow: hidden;
  background: linear-gradient(135deg, #0d3d6e 0%, #1A5FA8 50%, #d4861a 100%);
  flex-shrink: 0;
}
.bn img { width: 100%; height: 100%; object-fit: cover; display: block; }
.bn-logo {
  position: absolute; bottom: 14px; right: 20px; z-index: 2;
  width: 80px; height: 80px;
}
.bn-logo svg { width: 80px; height: 80px; display: block; }
.bn-overlay {
  position: absolute; inset: 0;
  background: linear-gradient(to bottom, transparent 50%, rgba(0,0,0,0.2));
}

/* CONTENT AREA centered, wide */
.pc {
  width: 100%;
  max-width: 860px;
  padding: 0 20px 50px;
}

/* EDIT BTN */
.ep {
  display: block; width: 100%;
  background: #1A5FA8; color: #fff;
  text-align: center; font-size: 14px; font-weight: 700;
  letter-spacing: .6px; padding: 14px;
  border-radius: 10px; text-decoration: none;
  font-family: inherit; border: none; cursor: pointer;
  margin: 14px 0 0;
  transition: background .15s;
}
.ep:hover { background: #155090; color: #fff; }

/* STATS */
.sw {
  display: flex; background: #fff;
  border-radius: 14px; margin-top: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.07);
  overflow: hidden;
}
.si {
  flex: 1; text-align: center; padding: 18px 8px;
  border-right: 1px solid #eef2f7;
  text-decoration: none; cursor: pointer;
}
.si:last-child { border-right: none; }
.si:hover { background: #f7faff; }
.si-ic {
  width: 48px; height: 48px; border-radius: 50%;
  background: #f0f0f0; margin: 0 auto 8px;
  display: flex; align-items: center; justify-content: center;
}
.si-ic svg { width: 22px; height: 22px; stroke: #888; fill: none; stroke-width: 1.8; }
.si-n { font-size: 20px; font-weight: 700; color: #0d1b2a; display: block; }
.si-l { font-size: 11px; color: #6b8099; display: block; margin-top: 3px; font-weight: 500; }

/* TWO COLUMN GRID */
.grid2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 12px; }
.grid2-full { grid-column: 1 / -1; }

/* CARD */
.cd {
  background: #fff; border-radius: 14px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.07);
  overflow: hidden;
}
.cd-mt { margin-top: 12px; }
.cd-h {
  display: flex; align-items: center; justify-content: space-between;
  padding: 14px 18px 10px;
}
.cd-t { font-size: 13px; font-weight: 700; color: #0d1b2a; }
.cd-a { font-size: 12px; color: #1A5FA8; font-weight: 600; text-decoration: none; }
.cd-a:hover { text-decoration: underline; }
.cd-b { padding: 0 18px 16px; font-size: 13px; color: #445566; line-height: 1.75; }

/* CONNECTIONS */
.cr {
  display: flex; gap: 14px;
  overflow-x: auto; padding: 0 18px 16px;
  scrollbar-width: none;
  flex-wrap: wrap;
}
.cr::-webkit-scrollbar { display: none; }
.ci { text-align: center; width: 64px; }
.ca {
  width: 58px; height: 58px; border-radius: 50%;
  margin: 0 auto 5px;
  display: flex; align-items: center; justify-content: center;
  font-size: 16px; font-weight: 700; color: #fff;
  overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.15);
  transition: transform .15s; cursor: pointer;
}
.ca:hover { transform: scale(1.07); }
.ca img { width: 100%; height: 100%; object-fit: cover; }
.cn { font-size: 11px; color: #445566; font-weight: 500; line-height: 1.3; word-break: break-word; }

/* BADGES */
.br {
  display: flex; gap: 14px;
  overflow-x: auto; padding: 0 18px 16px;
  scrollbar-width: none;
}
.br::-webkit-scrollbar { display: none; }
.bi { flex-shrink: 0; text-align: center; width: 72px; }
.bi-i {
  width: 64px; height: 64px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 5px; font-size: 28px; border: 3px solid;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  transition: transform .15s; cursor: pointer;
}
.bi-i:hover { transform: scale(1.08); }
.bi-n { font-size: 11px; color: #445566; font-weight: 500; }

/* POSTS */
.pi { border-bottom: 1px solid #eef2f7; padding: 14px 18px; }
.pi:last-child { border-bottom: none; padding-bottom: 16px; }
.pt { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; }
.pav {
  width: 36px; height: 36px; border-radius: 50%;
  background: #1A5FA8; display: flex; align-items: center; justify-content: center;
  font-size: 12px; font-weight: 700; color: #fff; flex-shrink: 0; overflow: hidden;
}
.pav img { width: 100%; height: 100%; object-fit: cover; }
.pan { font-size: 13px; font-weight: 600; color: #0d1b2a; }
.pat { font-size: 11px; color: #6b8099; }
.pb { font-size: 13px; color: #445566; line-height: 1.65; margin-bottom: 10px; }
.pa { display: flex; gap: 16px; }
.pac {
  display: flex; align-items: center; gap: 5px;
  font-size: 12px; color: #6b8099; border: none; background: none;
  cursor: pointer; padding: 0; font-family: inherit;
  text-decoration: none; transition: color .15s;
}
.pac:hover { color: #1A5FA8; }
.pac svg { width: 13px; height: 13px; }

/* ABOUT TABLE */
.ab-table { width: 100%; font-size: 13px; border-collapse: collapse; }
.ab-table td { padding: 6px 0; }
.ab-table tr { border-bottom: 1px solid #f0f4f8; }
.ab-table tr:last-child { border-bottom: none; }
</style>
@endpush

<div class="profile-wrap">

  {{-- BANNER full width --}}
  <div class="bn" style="width:100%">
    <img src="{{ asset('images/banner_unsri.jpg') }}"
         onerror="this.style.display='none'"
         alt="Banner Unsri">
    <div class="bn-overlay"></div>
    <div class="bn-logo">
      <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
        <circle cx="50" cy="50" r="48" fill="#F5A623" stroke="#c47d00" stroke-width="2"/>
        <circle cx="50" cy="50" r="40" fill="none" stroke="#fff" stroke-width="1.5"/>
        <text x="50" y="36" text-anchor="middle" font-size="9" font-weight="800" fill="#0d3d6e" font-family="sans-serif">UNIVERSITAS</text>
        <text x="50" y="48" text-anchor="middle" font-size="9" font-weight="800" fill="#0d3d6e" font-family="sans-serif">SRIWIJAYA</text>
        <text x="50" y="60" text-anchor="middle" font-size="6" fill="#0d3d6e" font-family="sans-serif">ILMU ALAT PENGABDIAN</text>
        <path d="M28 68 Q50 76 72 68" stroke="#0d3d6e" stroke-width="1.2" fill="none"/>
      </svg>
    </div>
  </div>

  {{-- CONTENT --}}
  <div class="pc">

    @if(session('success'))
      <div style="background:#E1F5EE;color:#0F6E56;border-left:4px solid #1D9E75;padding:10px 14px;border-radius:8px;font-size:13px;margin-top:14px">
        {{ session('success') }}
      </div>
    @endif

    {{-- EDIT PROFILE --}}
    <a href="{{ route('profile.edit') }}" class="ep">✎ &nbsp; EDIT PROFILE</a>

    {{-- STATS --}}
    <div class="sw">
      <a href="{{ route('connections.index') }}" class="si">
        <div class="si-ic">
          <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
        </div>
        <span class="si-n">{{ $connectionCount }}</span>
        <span class="si-l">connection</span>
      </a>
      <div class="si">
        <div class="si-ic">
          <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
        </div>
        <span class="si-n">0</span>
        <span class="si-l">badge</span>
      </div>
      <div class="si">
        <div class="si-ic">
          <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
        </div>
        <span class="si-n">0</span>
        <span class="si-l">event</span>
      </div>
    </div>

    {{-- TWO COLUMN --}}
    <div class="grid2">

      {{-- ABOUT --}}
      <div class="cd">
        <div class="cd-h"><div class="cd-t">About</div></div>
        <div class="cd-b">
          <p style="margin-bottom:12px">{{ $user->bio ?? $user->name . ' adalah anggota komunitas Universitas Sriwijaya.' }}</p>
          <table class="ab-table">
            <tr>
              <td style="color:#6b8099">User ID</td>
              <td style="font-weight:600;text-align:right">{{ $user->id }}</td>
            </tr>
            <tr>
              <td style="color:#6b8099">Email</td>
              <td style="font-weight:600;text-align:right">{{ $user->email }}</td>
            </tr>
            @if($user->phone ?? false)
            <tr>
              <td style="color:#6b8099">Telepon</td>
              <td style="font-weight:600;text-align:right">{{ $user->phone }}</td>
            </tr>
            @endif
            <tr>
              <td style="color:#6b8099">Bergabung</td>
              <td style="font-weight:600;text-align:right">{{ $user->created_at->format('d M Y') }}</td>
            </tr>
          </table>
        </div>
      </div>

      {{-- BADGES --}}
      <div class="cd">
        <div class="cd-h">
          <div class="cd-t">Badges</div>
          <a href="#" class="cd-a">View all</a>
        </div>
        <div class="br">
          <div class="bi"><div class="bi-i" style="background:#FAEEDA;border-color:#F5A623">🏆</div><div class="bi-n">Top Post</div></div>
          <div class="bi"><div class="bi-i" style="background:#E6F1FB;border-color:#1A5FA8">⭐</div><div class="bi-n">Star Member</div></div>
          <div class="bi"><div class="bi-i" style="background:#FAEEDA;border-color:#EF9F27">🥇</div><div class="bi-n">Kompetitor</div></div>
          <div class="bi"><div class="bi-i" style="background:#E1F5EE;border-color:#1D9E75">🥈</div><div class="bi-n">Runner Up</div></div>
          <div class="bi"><div class="bi-i" style="background:#FCEBEB;border-color:#E24B4A">🥉</div><div class="bi-n">3rd Place</div></div>
        </div>
      </div>

      {{-- CONNECTION full width --}}
      <div class="cd grid2-full">
        <div class="cd-h">
          <div class="cd-t">Connection</div>
          <a href="{{ route('connections.index') }}" class="cd-a">View all</a>
        </div>
        <div class="cr">
          @php $connBg = ['#1A5FA8','#0F6E56','#854F0B','#533AB7','#993556','#185FA5','#A32D2D','#5F5E5A']; @endphp
          @forelse($connections as $i => $conn)
            @php
              $cu = ($conn->requester_id == $user->id) ? $conn->receiver : $conn->requester;
              $p  = explode(' ', trim($cu->name));
              $in = strtoupper(substr($p[0],0,1)).(isset($p[1])?strtoupper(substr($p[1],0,1)):'');
            @endphp
            <div class="ci">
              <div class="ca" style="background:{{ $connBg[$i % count($connBg)] }}">
                @if($cu->avatar ?? false)
                  <img src="{{ asset('storage/'.$cu->avatar) }}" alt="">
                @else {{ $in }} @endif
              </div>
              <div class="cn">{{ $p[0] }}</div>
            </div>
          @empty
            <p style="font-size:12px;color:#6b8099;padding-bottom:10px">Belum ada koneksi.</p>
          @endforelse
        </div>
      </div>

      {{-- POSTS full width --}}
      @if(isset($posts) && $posts->count())
      <div class="cd grid2-full">
        <div class="cd-h">
          <div class="cd-t">Postingan Terbaru</div>
          <a href="{{ route('posts.index') }}" class="cd-a">View all</a>
        </div>
        {{-- 2 kolom post --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0">
          @foreach($posts as $post)
            @php
              $p2 = explode(' ', trim($user->name));
              $in2 = strtoupper(substr($p2[0],0,1)).(isset($p2[1])?strtoupper(substr($p2[1],0,1)):'');
            @endphp
            <div class="pi">
              <div class="pt">
                <div class="pav">
                  @if($user->avatar ?? false)<img src="{{ asset('storage/'.$user->avatar) }}" alt="">@else{{ $in2 }}@endif
                </div>
                <div>
                  <div class="pan">{{ $user->name }}</div>
                  <div class="pat">{{ $post->created_at->diffForHumans() }}</div>
                </div>
              </div>
              <p class="pb">{{ Str::limit($post->body, 120) }}</p>
              <div class="pa">
                <form method="POST" action="{{ route('posts.like', $post->id) }}" style="margin:0">
                  @csrf
                  <button type="submit" class="pac">
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M8 14s-6-4-6-8a4 4 0 018 0 4 4 0 018 0c0 4-6 8-6 8z"/></svg>
                    {{ $post->likes_count ?? 0 }}
                  </button>
                </form>
                <a href="{{ route('posts.show', $post->id) }}" class="pac">
                  <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H2a1 1 0 00-1 1v8a1 1 0 001 1h3l3 3 3-3h3a1 1 0 001-1V3a1 1 0 00-1-1z"/></svg>
                  {{ $post->comments_count ?? 0 }}
                </a>
                <a href="{{ route('posts.show', $post->id) }}" class="pac">
                  <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 3l4 4-4 4M5 7h8"/></svg>
                  Lihat
                </a>
              </div>
            </div>
          @endforeach
        </div>
      </div>
      @endif

    </div>{{-- end grid2 --}}
  </div>{{-- end pc --}}
</div>{{-- end profile-wrap --}}