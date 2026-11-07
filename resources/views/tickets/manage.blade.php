@extends('layouts.app')

@section('content')
<style>
    .ticket-page { min-height: 100vh; background: #efefef; padding: 0; font-family: Arial, Helvetica, sans-serif; }
    .ticket-shell { width: 100%; margin: 0 auto; background: #ffffff; min-height: 100vh; }
    .ticket-topbar { background: #2d7be8; color: #ffffff; text-align: center; font-size: 33px; font-weight: 700; line-height: 1.2; padding: 14px 20px 15px; position: relative; }
    .ticket-burger { display: none; position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 42px; height: 42px; border: 0; border-radius: 10px; background: rgba(255,255,255,0.16); color: #ffffff; cursor: pointer; align-items: center; justify-content: center; }
    .ticket-burger svg { width: 24px; height: 24px; }
    .ticket-mobile-overlay { display: none; }
    .ticket-body { display: flex; align-items: stretch; }
    .ticket-sidebar { width: 250px; background: #ffffff; border-right: 2px solid #cfcfcf; padding: 10px 8px 18px; display: flex; flex-direction: column; }
    .ticket-profile { display: flex; flex-direction: column; align-items: center; margin-top: 2px; margin-bottom: 30px; }
    .ticket-avatar { width: 70px; height: 70px; border: 4px solid #111111; border-radius: 999px; display: flex; align-items: center; justify-content: center; background: #ffffff; }
    .ticket-meta { margin-top: 8px; text-align: center; font-size: 11px; line-height: 1.15; font-weight: 700; color: #666666; }
    .ticket-nav { display: flex; flex-direction: column; gap: 20px; padding: 0 4px; }
    .ticket-sidebar-bottom { margin-top: auto; padding: 18px 4px 0; }
    .ticket-nav-item { display: flex; align-items: center; gap: 12px; min-height: 42px; border-radius: 14px; background: #eeeeee; padding: 0 14px; color: #1c1c1c; font-size: 22px; font-weight: 700; text-decoration: none; }
    .ticket-nav-item.active { background: #e3e3e3; }
    .ticket-nav-item svg { width: 26px; height: 26px; flex-shrink: 0; }
    .ticket-logout-btn { width: 100%; border: 0; cursor: pointer; text-align: left; font-family: Arial, Helvetica, sans-serif; }
    .ticket-main { flex: 1; background: #ffffff; padding: 18px 22px 16px; }
    .ticket-alert { margin-bottom: 12px; border: 1px solid #b7e3c4; background: #effbf2; color: #1d6b35; border-radius: 8px; padding: 10px 12px; font-size: 14px; font-weight: 600; }
    .ticket-hero { display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 26px; }
    .ticket-hero-title { margin: 0; color: #111111; font-size: 30px; line-height: 1.2; font-weight: 700; }
    .ticket-btn { display: inline-flex; align-items: center; gap: 4px; border-radius: 12px; background: #2d7be8; box-shadow: 0 3px 10px rgba(0, 0, 0, 0.22); color: #ffffff; text-decoration: none; font-size: 24px; font-weight: 700; line-height: 1; padding: 10px 14px 11px; white-space: nowrap; }
    .ticket-btn .plus { font-size: 39px; line-height: 0.8; font-weight: 700; margin-right: 2px; }
    .ticket-stats-wrap { background: #d9d9d9; border-radius: 18px; padding: 16px 14px 18px; margin-bottom: 24px; }
    .ticket-stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 18px; }
    .ticket-card { background: #ffffff; border: 2px solid #838383; border-radius: 12px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.18); padding: 10px 12px 8px; }
    .ticket-card-row { display: flex; align-items: flex-start; gap: 10px; }
    .ticket-card-icon { width: 32px; height: 32px; object-fit: contain; flex-shrink: 0; }
    .ticket-card-title { color: #141414; font-size: 15px; font-weight: 700; line-height: 1.1; margin-top: 1px; }
    .ticket-card-value { color: #111111; font-size: 16px; font-weight: 700; line-height: 1; margin-top: 5px; }
    .ticket-progress { width: 88px; height: 4px; border-radius: 999px; background: #e6e6e6; margin-top: 4px; }
    .ticket-progress > span { display: block; height: 4px; border-radius: 999px; }
    .progress-blue { background: #4aa8ff; } .progress-yellow { background: #efb028; } .progress-green { background: #47e238; } .progress-red { background: #ff3030; }
    .ticket-note { margin: 0 0 18px; border-radius: 14px; background: #f5f5f5; border: 1px solid #e2e2e2; padding: 14px 16px; color: #333333; font-size: 14px; line-height: 1.5; }
    .ticket-note strong { color: #111111; }
    .ticket-section-title { margin: 0 0 8px; color: #111111; font-size: 21px; font-weight: 700; line-height: 1.2; }
    .ticket-table { width: 100%; border-collapse: collapse; }
    .ticket-table thead tr { background: #ebebeb; }
    .ticket-table th { color: #111111; text-align: left; font-size: 14px; font-weight: 700; padding: 4px 10px; }
    .ticket-table td { color: #202020; font-size: 13px; padding: 9px 10px; vertical-align: middle; }
    .status-pill { display: inline-flex; align-items: center; justify-content: center; min-width: 98px; border-radius: 999px; color: #111111; font-size: 12px; font-weight: 700; padding: 4px 14px; }
    .status-proses { background: #eda400; }
    .status-selesai { background: #4cff2b; }
    .status-pending { background: #ff3030; }
    .ticket-link { color: #1f1f1f; text-decoration: none; }
    .logout-overlay { position: fixed; inset: 0; background: rgba(0, 0, 0, 0.34); display: none; align-items: center; justify-content: center; z-index: 999; }
    .logout-overlay.active { display: flex; }
    .logout-modal { width: 100%; max-width: 230px; border-radius: 16px; background: #ffffff; padding: 12px 10px 10px; box-shadow: 0 10px 24px rgba(0, 0, 0, 0.18); }
    .logout-modal-title { color: #111111; font-size: 14px; font-weight: 700; margin: 0 0 4px; }
    .logout-modal-text { color: #c7c7c7; font-size: 10px; font-weight: 600; margin: 0 0 12px; }
    .logout-modal-actions { display: flex; justify-content: flex-end; gap: 6px; }
    .logout-modal-btn { border: 0; border-radius: 8px; padding: 6px 12px; font-size: 12px; font-weight: 700; cursor: pointer; line-height: 1; }
    .logout-cancel { background: #f2b705; color: #111111; }
    .logout-confirm { background: #2d7be8; color: #ffffff; }
    @media (max-width: 900px) {
        .ticket-burger { display: inline-flex; }
        .ticket-mobile-overlay.active { display: block; position: fixed; inset: 0; background: rgba(0,0,0,0.28); z-index: 998; }
        .ticket-sidebar { position: fixed; top: 0; left: 0; bottom: 0; width: min(280px,84vw); transform: translateX(-100%); transition: transform 0.25s ease; z-index: 999; overflow-y: auto; }
        .ticket-sidebar.mobile-open { transform: translateX(0); }
        .ticket-main { padding: 16px; }
        .ticket-hero { flex-direction: column; align-items: flex-start; }
        .ticket-stats { grid-template-columns: 1fr; }
        .ticket-nav-item { font-size: 18px; }
        .ticket-hero-title { font-size: 24px; }
    }
</style>

<div class="ticket-page">
    <div class="ticket-shell">
        <div class="ticket-topbar"><button type="button" class="ticket-burger" data-sidebar-open><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4 6h16v2H4V6zm0 5h16v2H4v-2zm0 5h16v2H4v-2z"/></svg></button>{{ $user->role === 'admin' ? 'Dashboard Admin' : 'Dashboard Asisten' }}</div>
        <div class="ticket-body">
            <aside class="ticket-sidebar" id="mobileSidebar">
                <div class="ticket-profile">
                    <div class="ticket-avatar">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:38px;height:38px;color:#111"><path d="M12 12a5 5 0 100-10 5 5 0 000 10zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z"/></svg>
                    </div>
                    <div class="ticket-meta">
                        <div>{{ $user->displayName() }}</div>
                        <div>{{ $user->gender ?? 'Gender' }}</div>
                        <div>{{ $user->email ?? 'Email' }}</div>
                    </div>
                </div>
                <nav class="ticket-nav">
                    <a href="{{ route('profile') }}" class="ticket-nav-item">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a5 5 0 100-10 5 5 0 000 10zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z"/></svg>
                        <span>Profil</span>
                    </a>
                    <a href="{{ $user->role === 'admin' ? route('admin.dashboard') : route('asisten.dashboard') }}" class="ticket-nav-item">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3l9 8h-3v10h-5v-6H11v6H6V11H3l9-8z"/></svg>
                        <span>Home</span>
                    </a>
                    @if($user->role === 'admin')
                    <a href="{{ route('labs.index') }}" class="ticket-nav-item">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M19 11H13V5h-2v6H5v2h6v6h2v-6h6v-2z"/></svg>
                        <span>Kelola Lab</span>
                    </a>
                    <a href="{{ route('admin.assistants.index') }}" class="ticket-nav-item">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M15 14c2.761 0 5 1.567 5 3.5V19h-7v-1.5c0-1.064.679-2.017 1.742-2.65A5.964 5.964 0 0115 14zm-8-2a4 4 0 110-8 4 4 0 010 8zm0 2c3.314 0 6 1.567 6 3.5V19H1v-1.5C1 15.567 3.686 14 7 14z"/></svg>
                        <span>Asisten</span>
                    </a>
                    @endif
                    <a href="{{ route('schedules.index') }}" class="ticket-nav-item">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M7 2h2v2h6V2h2v2h3v18H4V4h3V2zm11 8H6v10h12V10zM8 12h4v4H8v-4z"/></svg>
                        <span>Jadwal</span>
                    </a>
                </nav>
                <div class="ticket-sidebar-bottom">
                    <button type="button" class="ticket-nav-item ticket-logout-btn" data-open-logout>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12H3m0 0l4-4m-4 4l4 4m5-9h5a2 2 0 012 2v6a2 2 0 01-2 2h-5"/></svg>
                        <span>Logout</span>
                    </button>
                </div>
            </aside>
            <main class="ticket-main">
                @if(session('success'))
                <div class="ticket-alert">{{ session('success') }}</div>
                @endif
                <div class="ticket-hero">
                    <h2 class="ticket-hero-title">Ticket Masuk</h2>
                    <a href="{{ route('tickets.index') }}" class="ticket-btn"><span class="plus">+</span><span>Refresh</span></a>
                </div>
                <div class="ticket-stats-wrap">
                    <div class="ticket-stats">
                        <div class="ticket-card"><div class="ticket-card-row"><img class="ticket-card-icon" src="{{ asset('images/image.png') }}" alt="Total"><div><div class="ticket-card-title">Total Ticket</div><div class="ticket-card-value">{{ $total }}</div><div class="ticket-progress"><span class="progress-blue" style="width:100%"></span></div></div></div></div>
                        <div class="ticket-card"><div class="ticket-card-row"><img class="ticket-card-icon" src="{{ asset('images/image2.png') }}" alt="Pending"><div><div class="ticket-card-title">Pending</div><div class="ticket-card-value">{{ $pending }}</div><div class="ticket-progress"><span class="progress-red" style="width:100%"></span></div></div></div></div>
                        <div class="ticket-card"><div class="ticket-card-row"><img class="ticket-card-icon" src="{{ asset('images/image2.png') }}" alt="Proses"><div><div class="ticket-card-title">Diproses</div><div class="ticket-card-value">{{ $proses }}</div><div class="ticket-progress"><span class="progress-yellow" style="width:100%"></span></div></div></div></div>
                        <div class="ticket-card"><div class="ticket-card-row"><img class="ticket-card-icon" src="{{ asset('images/image3.png') }}" alt="Selesai"><div><div class="ticket-card-title">Selesai</div><div class="ticket-card-value">{{ $selesai }}</div><div class="ticket-progress"><span class="progress-green" style="width:100%"></span></div></div></div></div>
                    </div>
                </div>
                <p class="ticket-note"><strong>Keterangan Penanganan Ticket:</strong> Halaman ini menampilkan semua ticket yang dikirim user. Admin dan asisten dapat membuka ticket lalu mengubah statusnya menjadi diproses atau selesai saat penanganan dilakukan.</p>
                <section>
                    <h3 class="ticket-section-title">Daftar Ticket User</h3>
                    <table class="ticket-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Pelapor</th>
                                <th>Lab</th>
                                <th>PC-ID</th>
                                <th>Judul</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                            @php
                                $statusClass = $ticket->status === 'proses' ? 'status-proses' : ($ticket->status === 'selesai' ? 'status-selesai' : 'status-pending');
                                $statusLabel = $ticket->status === 'proses' ? 'Diproses' : ($ticket->status === 'selesai' ? 'Selesai' : 'Pending');
                            @endphp
                            <tr>
                                <td>#{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $ticket->nama_pelapor ?: 'Anon' }}</td>
                                <td>{{ $ticket->lab->nama_lab ?? '-' }}</td>
                                <td>PC-{{ str_pad($ticket->nomor_pc, 2, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $ticket->judul }}</td>
                                <td>{{ $ticket->created_at->format('d M Y') }}</td>
                                <td><span class="status-pill {{ $statusClass }}">{{ $statusLabel }}</span></td>
                                <td><a href="{{ route('tickets.edit', $ticket) }}" class="ticket-link">Tangani</a></td>
                            </tr>
                            @empty
                            <tr><td colspan="8" style="padding:14px 10px;color:#666;font-size:13px;">Belum ada ticket dari user.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </section>
            </main>
        </div>
    </div>
</div>
<div class="ticket-mobile-overlay" id="mobileSidebarOverlay"></div>

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
