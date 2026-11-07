@extends('layouts.app')

@section('content')
<style>
    .student-dashboard { min-height: 100vh; background: #efefef; padding: 0; font-family: Arial, Helvetica, sans-serif; }
    .student-shell { width: 100%; margin: 0 auto; background: #ffffff; min-height: 100vh; }
    .student-topbar { background: #2d7be8; color: #ffffff; text-align: center; font-size: 33px; font-weight: 700; line-height: 1.2; padding: 14px 20px 15px; position: relative; }
    .student-burger { display:none; position:absolute; left:14px; top:50%; transform:translateY(-50%); width:42px; height:42px; border:0; border-radius:10px; background:rgba(255,255,255,0.16); color:#fff; cursor:pointer; align-items:center; justify-content:center; }
    .student-burger svg { width:24px; height:24px; }
    .student-mobile-overlay { display:none; }
    .student-body { display: flex; align-items: stretch; }
    .student-sidebar { width: 250px; background: #ffffff; border-right: 2px solid #cfcfcf; padding: 10px 8px 18px; display: flex; flex-direction: column; }
    .student-profile { display: flex; flex-direction: column; align-items: center; margin-top: 2px; margin-bottom: 30px; }
    .student-avatar { width: 70px; height: 70px; border: 4px solid #111111; border-radius: 999px; display: flex; align-items: center; justify-content: center; background: #ffffff; }
    .student-meta { margin-top: 8px; text-align: center; font-size: 11px; line-height: 1.15; font-weight: 700; color: #666666; }
    .student-nav { display: flex; flex-direction: column; gap: 20px; padding: 0 4px; }
    .student-sidebar-bottom { margin-top: auto; padding: 18px 4px 0; }
    .student-nav-item { display: flex; align-items: center; gap: 12px; min-height: 42px; border-radius: 14px; background: #eeeeee; padding: 0 14px; color: #1c1c1c; font-size: 22px; font-weight: 700; text-decoration: none; }
    .student-nav-item svg { width: 26px; height: 26px; flex-shrink: 0; }
    .student-nav-item.active { background: #e3e3e3; }
    .student-logout-btn { width: 100%; border: 0; cursor: pointer; text-align: left; font-family: Arial, Helvetica, sans-serif; }
    .student-main { flex: 1; background: #ffffff; padding: 18px 22px 16px; }
    .student-alert { margin-bottom: 12px; border: 1px solid #b7e3c4; background: #effbf2; color: #1d6b35; border-radius: 8px; padding: 10px 12px; font-size: 14px; font-weight: 600; }
    .student-hero { display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 26px; }
    .student-hero-title { margin: 0; color: #111111; font-size: 30px; line-height: 1.2; font-weight: 700; }
    .student-ticket-btn { display: inline-flex; align-items: center; gap: 4px; border-radius: 12px; background: #2d7be8; box-shadow: 0 3px 10px rgba(0,0,0,0.22); color: #ffffff; text-decoration: none; font-size: 24px; font-weight: 700; line-height: 1; padding: 10px 14px 11px; white-space: nowrap; }
    .student-ticket-btn .plus { font-size: 39px; line-height: 0.8; font-weight: 700; margin-right: 2px; }
    .detail-wrap { background: #d9d9d9; border-radius: 18px; padding: 16px 14px 18px; margin-bottom: 28px; }
    .detail-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px; }
    .detail-card { background: #ffffff; border: 2px solid #838383; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.18); padding: 12px 14px 10px; }
    .detail-card-row { display: flex; align-items: flex-start; gap: 10px; }
    .detail-card-icon { width: 32px; height: 32px; object-fit: contain; flex-shrink: 0; }
    .detail-card-title { color: #141414; font-size: 15px; font-weight: 700; line-height: 1.1; margin-top: 1px; }
    .detail-card-value { color: #111111; font-size: 22px; font-weight: 700; line-height: 1.1; margin-top: 8px; }
    .detail-progress { width: 88px; height: 4px; border-radius: 999px; background: #e6e6e6; margin-top: 7px; }
    .detail-progress > span { display: block; width: 100%; height: 4px; border-radius: 999px; }
    .progress-blue { background: #4aa8ff; }
    .progress-yellow { background: #efb028; }
    .student-section-title { margin: 0 0 12px; color: #111111; font-size: 21px; font-weight: 700; line-height: 1.2; }
    .detail-panel { background: #ffffff; border: 2px solid #d8d8d8; border-radius: 18px; padding: 18px 20px; }
    .detail-list { display: grid; grid-template-columns: 180px 1fr; row-gap: 14px; column-gap: 10px; }
    .detail-label { color: #666666; font-size: 15px; font-weight: 700; }
    .detail-text { color: #151515; font-size: 16px; font-weight: 700; }
    .detail-actions { display: flex; gap: 14px; margin-top: 24px; }
    .detail-action { display: inline-flex; align-items: center; justify-content: center; min-width: 170px; border-radius: 12px; text-decoration: none; font-size: 20px; font-weight: 700; line-height: 1; padding: 14px 18px; box-shadow: 0 3px 10px rgba(0,0,0,0.16); }
    .detail-action-primary { background: #2d7be8; color: #ffffff; }
    .detail-action-secondary { background: #eeeeee; color: #1c1c1c; }
    .logout-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.34); display: none; align-items: center; justify-content: center; z-index: 999; }
    .logout-overlay.active { display: flex; }
    .logout-modal { width: 100%; max-width: 230px; border-radius: 16px; background: #ffffff; padding: 12px 10px 10px; box-shadow: 0 10px 24px rgba(0,0,0,0.18); }
    .logout-modal-title { color: #111111; font-size: 14px; font-weight: 700; margin: 0 0 4px; }
    .logout-modal-text { color: #c7c7c7; font-size: 10px; font-weight: 600; margin: 0 0 12px; }
    .logout-modal-actions { display: flex; justify-content: flex-end; gap: 6px; }
    .logout-modal-btn { border: 0; border-radius: 8px; padding: 6px 12px; font-size: 12px; font-weight: 700; cursor: pointer; line-height: 1; }
    .logout-cancel { background: #f2b705; color: #111111; }
    .logout-confirm { background: #2d7be8; color: #ffffff; }

    @media (max-width: 900px) {
        .student-burger { display:inline-flex; }
        .student-mobile-overlay.active { display:block; position:fixed; inset:0; background:rgba(0,0,0,0.28); z-index:998; }
        .student-sidebar { position:fixed; top:0; left:0; bottom:0; width:min(280px,84vw); transform:translateX(-100%); transition:transform 0.25s ease; z-index:999; overflow-y:auto; }
        .student-sidebar.mobile-open { transform:translateX(0); }
        .student-main { padding: 16px; }
        .student-hero { flex-direction: column; align-items: flex-start; }
        .detail-grid { grid-template-columns: 1fr; }
        .detail-list { grid-template-columns: 1fr; row-gap: 6px; }
        .detail-actions { flex-direction: column; }
        .detail-action { width: 100%; }
    }
</style>

<div class="student-dashboard">
    <div class="student-shell">
        <div class="student-topbar"><button type="button" class="student-burger" data-sidebar-open><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4 6h16v2H4V6zm0 5h16v2H4v-2zm0 5h16v2H4v-2z"/></svg></button>Dashboard Admin</div>

        <div class="student-body">
            <aside class="student-sidebar" id="mobileSidebar">
                <div class="student-profile">
                    <div class="student-avatar">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:38px;height:38px;color:#111">
                            <path d="M12 12a5 5 0 100-10 5 5 0 000 10zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z"/>
                        </svg>
                    </div>
                    <div class="student-meta">
                        <div>{{ auth()->user()->displayName() }}</div>
                        <div>{{ auth()->user()->gender ?? 'Gender' }}</div>
                        <div>{{ auth()->user()->email ?? 'Email' }}</div>
                    </div>
                </div>

                <nav class="student-nav">
                    <a href="{{ route('profile') }}" class="student-nav-item">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 12a5 5 0 100-10 5 5 0 000 10zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z"/>
                        </svg>
                        <span>Profil</span>
                    </a>

                    <a href="{{ route('admin.dashboard') }}" class="student-nav-item">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 3l9 8h-3v10h-5v-6H11v6H6V11H3l9-8z"/>
                        </svg>
                        <span>Home</span>
                    </a>

                    <a href="{{ route('labs.index') }}" class="student-nav-item active">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19 11H13V5h-2v6H5v2h6v6h2v-6h6v-2z"/>
                        </svg>
                        <span>Kelola Lab</span>
                    </a>

                    <a href="{{ route('admin.assistants.index') }}" class="student-nav-item">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M15 14c2.761 0 5 1.567 5 3.5V19h-7v-1.5c0-1.064.679-2.017 1.742-2.65A5.964 5.964 0 0115 14zm-8-2a4 4 0 110-8 4 4 0 010 8zm0 2c3.314 0 6 1.567 6 3.5V19H1v-1.5C1 15.567 3.686 14 7 14z"/>
                        </svg>
                        <span>Asisten</span>
                    </a>

                    <a href="{{ route('schedules.index') }}" class="student-nav-item">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M7 2h2v2h6V2h2v2h3v18H4V4h3V2zm11 8H6v10h12V10zM8 12h4v4H8v-4z"/>
                        </svg>
                        <span>Jadwal</span>
                    </a>

                    <a href="{{ route('histories.index') }}" class="student-nav-item">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M5 4h14v2H5V4zm0 4h14v12H5V8zm2 2v8h10v-8H7z"/>
                        </svg>
                        <span>Riwayat Aset</span>
                    </a>

                </nav>

                <div class="student-sidebar-bottom">
                    <button type="button" class="student-nav-item student-logout-btn" data-open-logout>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H3m0 0l4-4m-4 4l4 4m5-9h5a2 2 0 012 2v6a2 2 0 01-2 2h-5"/>
                        </svg>
                        <span>Logout</span>
                    </button>
                </div>
            </aside>

            <main class="student-main">
                @if(session('success'))
                <div class="student-alert">{{ session('success') }}</div>
                @endif

                <div class="student-hero">
                    <h2 class="student-hero-title">Detail Lab {{ $lab->nama_lab }}</h2>
                    <a href="{{ route('labs.edit', $lab) }}" class="student-ticket-btn">
                        <span class="plus">+</span>
                        <span>Edit Lab</span>
                    </a>
                </div>

                <div class="detail-wrap">
                    <div class="detail-grid">
                        <div class="detail-card">
                            <div class="detail-card-row">
                                <img class="detail-card-icon" src="{{ asset('images/image.png') }}" alt="Lab">
                                <div>
                                    <div class="detail-card-title">Nama Lab</div>
                                    <div class="detail-card-value">{{ $lab->nama_lab }}</div>
                                    <div class="detail-progress"><span class="progress-blue"></span></div>
                                </div>
                            </div>
                        </div>

                        <div class="detail-card">
                            <div class="detail-card-row">
                                <img class="detail-card-icon" src="{{ asset('images/image2.png') }}" alt="PC">
                                <div>
                                    <div class="detail-card-title">Jumlah PC</div>
                                    <div class="detail-card-value">{{ $lab->pc_count }}</div>
                                    <div class="detail-progress"><span class="progress-yellow"></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <section>
                    <h3 class="student-section-title">Informasi Lab</h3>
                    <div class="detail-panel">
                        <div class="detail-list">
                            <div class="detail-label">ID Lab</div>
                            <div class="detail-text">#{{ str_pad($lab->id, 3, '0', STR_PAD_LEFT) }}</div>

                            <div class="detail-label">Nama Lab</div>
                            <div class="detail-text">{{ $lab->nama_lab }}</div>

                            <div class="detail-label">Jumlah PC</div>
                            <div class="detail-text">{{ $lab->pc_count }} Unit</div>
                        </div>

                        <div class="detail-actions">
                            <a href="{{ route('labs.edit', $lab) }}" class="detail-action detail-action-primary">Edit Lab</a>
                            <a href="{{ route('labs.index') }}" class="detail-action detail-action-secondary">Kembali</a>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>
</div>
<div class="student-mobile-overlay" id="mobileSidebarOverlay"></div>

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
