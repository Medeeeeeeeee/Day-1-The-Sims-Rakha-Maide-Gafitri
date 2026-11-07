@extends('layouts.app')

@section('content')
<style>
    .history-page { min-height: 100vh; background: #efefef; padding: 0; font-family: Arial, Helvetica, sans-serif; }
    .history-shell { width: 100%; margin: 0 auto; background: #ffffff; min-height: 100vh; }
    .history-topbar { background: #2d7be8; color: #ffffff; text-align: center; font-size: 33px; font-weight: 700; line-height: 1.2; padding: 14px 20px 15px; position: relative; }
    .history-burger { display:none; position:absolute; left:14px; top:50%; transform:translateY(-50%); width:42px; height:42px; border:0; border-radius:10px; background:rgba(255,255,255,0.16); color:#fff; cursor:pointer; align-items:center; justify-content:center; }
    .history-burger svg { width:24px; height:24px; }
    .history-mobile-overlay { display:none; }
    .history-body { display:flex; align-items:stretch; }
    .history-sidebar { width:250px; background:#fff; border-right:2px solid #cfcfcf; padding:10px 8px 18px; display:flex; flex-direction:column; }
    .history-profile { display:flex; flex-direction:column; align-items:center; margin-top:2px; margin-bottom:30px; }
    .history-avatar { width:70px; height:70px; border:4px solid #111; border-radius:999px; display:flex; align-items:center; justify-content:center; background:#fff; }
    .history-meta { margin-top:8px; text-align:center; font-size:11px; line-height:1.15; font-weight:700; color:#666; }
    .history-nav { display:flex; flex-direction:column; gap:20px; padding:0 4px; }
    .history-sidebar-bottom { margin-top:auto; padding:18px 4px 0; }
    .history-nav-item { display:flex; align-items:center; gap:12px; min-height:42px; border-radius:14px; background:#eeeeee; padding:0 14px; color:#1c1c1c; font-size:22px; font-weight:700; text-decoration:none; }
    .history-nav-item.active { background:#e3e3e3; }
    .history-nav-item svg { width:26px; height:26px; flex-shrink:0; }
    .history-logout-btn { width:100%; border:0; cursor:pointer; text-align:left; font-family:Arial, Helvetica, sans-serif; }
    .history-main { flex:1; background:#fff; padding:18px 22px 16px; }
    .history-hero { display:flex; align-items:center; justify-content:space-between; gap:16px; margin-bottom:22px; }
    .history-hero-title { margin:0; color:#111; font-size:30px; line-height:1.2; font-weight:700; }
    .history-hero-actions { display:flex; gap:12px; flex-wrap:wrap; }
    .history-btn { display:inline-flex; align-items:center; gap:4px; border-radius:12px; background:#2d7be8; box-shadow:0 3px 10px rgba(0,0,0,0.22); color:#fff; text-decoration:none; font-size:22px; font-weight:700; line-height:1; padding:10px 14px 11px; white-space:nowrap; }
    .history-btn.secondary { background:#5b5b5b; }
    .history-filter { display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end; margin-bottom:18px; }
    .history-field label { display:block; color:#111; font-size:14px; font-weight:700; margin-bottom:6px; }
    .history-field select, .history-field input { height:42px; min-width:180px; border:1px solid #d3d3d3; border-radius:10px; padding:0 12px; font-size:14px; }
    .history-submit { height:42px; border:0; border-radius:10px; background:#2d7be8; color:#fff; font-size:14px; font-weight:700; padding:0 18px; cursor:pointer; }
    .history-note { margin:0 0 18px; border-radius:14px; background:#f5f5f5; border:1px solid #e2e2e2; padding:14px 16px; color:#333; font-size:14px; line-height:1.5; }
    .history-note strong { color:#111; }
    .history-section-title { margin:0 0 8px; color:#111; font-size:21px; font-weight:700; line-height:1.2; }
    .history-table { width:100%; border-collapse:collapse; }
    .history-table thead tr { background:#ebebeb; }
    .history-table th { color:#111; text-align:left; font-size:14px; font-weight:700; padding:4px 10px; }
    .history-table td { color:#202020; font-size:13px; padding:9px 10px; vertical-align:middle; }
    .logout-overlay { position:fixed; inset:0; background:rgba(0,0,0,0.34); display:none; align-items:center; justify-content:center; z-index:999; }
    .logout-overlay.active { display:flex; }
    .logout-modal { width:100%; max-width:230px; border-radius:16px; background:#fff; padding:12px 10px 10px; box-shadow:0 10px 24px rgba(0,0,0,0.18); }
    .logout-modal-title { color:#111; font-size:14px; font-weight:700; margin:0 0 4px; }
    .logout-modal-text { color:#c7c7c7; font-size:10px; font-weight:600; margin:0 0 12px; }
    .logout-modal-actions { display:flex; justify-content:flex-end; gap:6px; }
    .logout-modal-btn { border:0; border-radius:8px; padding:6px 12px; font-size:12px; font-weight:700; cursor:pointer; line-height:1; }
    .logout-cancel { background:#f2b705; color:#111; }
    .logout-confirm { background:#2d7be8; color:#fff; }
    @media (max-width: 900px) {
        .history-burger { display:inline-flex; }
        .history-mobile-overlay.active { display:block; position:fixed; inset:0; background:rgba(0,0,0,0.28); z-index:998; }
        .history-sidebar { position:fixed; top:0; left:0; bottom:0; width:min(280px,84vw); transform:translateX(-100%); transition:transform 0.25s ease; z-index:999; overflow-y:auto; }
        .history-sidebar.mobile-open { transform:translateX(0); }
        .history-main { padding:16px; }
        .history-hero { flex-direction:column; align-items:flex-start; }
        .history-filter { flex-direction:column; align-items:stretch; }
        .history-field select, .history-field input, .history-submit { width:100%; min-width:0; }
    }
</style>

<div class="history-page">
    <div class="history-shell">
        <div class="history-topbar">
            <button type="button" class="history-burger" data-sidebar-open>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4 6h16v2H4V6zm0 5h16v2H4v-2zm0 5h16v2H4v-2z"/></svg>
            </button>
            {{ $user->role === 'supervisor' ? 'Dashboard Admin' : 'Dashboard Asisten' }}
        </div>

        <div class="history-body">
            <aside class="history-sidebar" id="mobileSidebar">
                <div class="history-profile">
                    <div class="history-avatar">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:38px;height:38px;color:#111"><path d="M12 12a5 5 0 100-10 5 5 0 000 10zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z"/></svg>
                    </div>
                    <div class="history-meta">
                        <div>{{ $user->displayName() }}</div>
                        <div>{{ $user->gender ?? 'Gender' }}</div>
                        <div>{{ $user->email ?? 'Email' }}</div>
                    </div>
                </div>
                <nav class="history-nav">
                    <a href="{{ route('profile') }}" class="history-nav-item"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a5 5 0 100-10 5 5 0 000 10zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z"/></svg><span>Profil</span></a>
                    <a href="{{ $user->role === 'supervisor' ? route('admin.dashboard') : route('asisten.dashboard') }}" class="history-nav-item"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3l9 8h-3v10h-5v-6H11v6H6V11H3l9-8z"/></svg><span>Home</span></a>
                    @if($user->role === 'supervisor')
                    <a href="{{ route('labs.index') }}" class="history-nav-item"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M19 11H13V5h-2v6H5v2h6v6h2v-6h6v-2z"/></svg><span>Kelola Lab</span></a>
                    <a href="{{ route('admin.assistants.index') }}" class="history-nav-item"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M15 14c2.761 0 5 1.567 5 3.5V19h-7v-1.5c0-1.064.679-2.017 1.742-2.65A5.964 5.964 0 0115 14zm-8-2a4 4 0 110-8 4 4 0 010 8zm0 2c3.314 0 6 1.567 6 3.5V19H1v-1.5C1 15.567 3.686 14 7 14z"/></svg><span>Asisten</span></a>
                    @endif
                    <a href="{{ route('schedules.index') }}" class="history-nav-item"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M7 2h2v2h6V2h2v2h3v18H4V4h3V2zm11 8H6v10h12V10zM8 12h4v4H8v-4z"/></svg><span>Jadwal</span></a>
                    <a href="{{ route('histories.index') }}" class="history-nav-item active"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M5 4h14v2H5V4zm0 4h14v12H5V8zm2 2v8h10v-8H7z"/></svg><span>Riwayat Aset</span></a>
                </nav>
                <div class="history-sidebar-bottom">
                    <button type="button" class="history-nav-item history-logout-btn" data-open-logout><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12H3m0 0l4-4m-4 4l4 4m5-9h5a2 2 0 012 2v6a2 2 0 01-2 2h-5"/></svg><span>Logout</span></button>
                </div>
            </aside>

            <main class="history-main">
                <div class="history-hero">
                    <h2 class="history-hero-title">Riwayat Aset</h2>
                    <div class="history-hero-actions">
                        <a href="{{ route('histories.print', request()->query()) }}" target="_blank" class="history-btn secondary">Cetak Laporan</a>
                    </div>
                </div>

                <form method="GET" class="history-filter">
                    <div class="history-field">
                        <label for="lab_id">Lab</label>
                        <select id="lab_id" name="lab_id">
                            <option value="">Semua Lab</option>
                            @foreach($labs as $lab)
                            <option value="{{ $lab->id }}" {{ (string) request('lab_id') === (string) $lab->id ? 'selected' : '' }}>{{ $lab->nama_lab }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="history-field">
                        <label for="nomor_pc">Asset / PC</label>
                        <input id="nomor_pc" type="number" name="nomor_pc" min="1" value="{{ request('nomor_pc') }}" placeholder="Contoh: 1">
                    </div>
                    <button class="history-submit" type="submit">Filter</button>
                </form>

                <p class="history-note">
                    <strong>Keterangan Riwayat Aset:</strong>
                    Halaman ini menampilkan riwayat penanganan berdasarkan laboratorium dan aset yang terhubung dengan ticket. Jika sebuah maintenance berasal dari ticket user, nomor asset/PC akan ditampilkan agar riwayat per aset tetap bisa dilacak.
                </p>

                <section>
                    <h3 class="history-section-title">Daftar Riwayat</h3>
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Lab</th>
                                <th>Aset</th>
                                <th>Judul Ticket</th>
                                <th>Asisten</th>
                                <th>Tindakan</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($histories as $history)
                            <tr>
                                <td>#{{ str_pad($history->id, 3, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $history->lab->nama_lab ?? '-' }}</td>
                                <td>
                                    @php
                                        $assetPc = $history->ticket?->nomor_pc ?: ($history->nomor_pc > 0 ? $history->nomor_pc : null);
                                    @endphp
                                    {{ $assetPc ? 'PC-' . str_pad($assetPc, 2, '0', STR_PAD_LEFT) : 'Per Lab' }}
                                </td>
                                <td>{{ $history->ticket?->judul ?? '-' }}</td>
                                <td>{{ $history->asisten?->displayName() ?: ($history->asisten->email ?? '-') }}</td>
                                <td>{{ $history->tindakan }}</td>
                                <td>{{ \Illuminate\Support\Carbon::parse($history->tanggal_perawatan)->format('d M Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="7" style="padding:14px 10px;color:#666;font-size:13px;">Belum ada riwayat aset.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </section>
            </main>
        </div>
    </div>
</div>
<div class="history-mobile-overlay" id="mobileSidebarOverlay"></div>

<div class="logout-overlay" id="logoutOverlay">
    <div class="logout-modal">
        <p class="logout-modal-title">Keluar</p>
        <p class="logout-modal-text">Apakah anda yakin untuk keluar ?</p>
        <div class="logout-modal-actions">
            <button type="button" class="logout-modal-btn logout-cancel" id="logoutCancel">Tidak</button>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-modal-btn logout-confirm">Ya</button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const overlay = document.getElementById('logoutOverlay');
    const cancelBtn = document.getElementById('logoutCancel');
    const mobileSidebar = document.getElementById('mobileSidebar');
    const mobileOverlay = document.getElementById('mobileSidebarOverlay');
    const sidebarOpen = document.querySelector('[data-sidebar-open]');

    if (sidebarOpen) {
        sidebarOpen.addEventListener('click', function () {
            mobileSidebar.classList.add('mobile-open');
            mobileOverlay.classList.add('active');
        });
    }

    if (mobileOverlay) {
        mobileOverlay.addEventListener('click', function () {
            mobileSidebar.classList.remove('mobile-open');
            mobileOverlay.classList.remove('active');
        });
    }

    document.querySelectorAll('[data-open-logout]').forEach((button) => {
        button.addEventListener('click', function () {
            overlay.classList.add('active');
        });
    });

    cancelBtn.addEventListener('click', function () {
        overlay.classList.remove('active');
    });

    overlay.addEventListener('click', function (event) {
        if (event.target === overlay) {
            overlay.classList.remove('active');
        }
    });
});
</script>
@endsection
