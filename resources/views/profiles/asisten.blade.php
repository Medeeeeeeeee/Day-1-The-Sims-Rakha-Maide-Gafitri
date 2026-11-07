@extends('layouts.app')

@section('content')
<style>
    .assistant-page {
        min-height: 100vh;
        background: #efefef;
        padding: 0;
        font-family: Arial, Helvetica, sans-serif;
    }
    .assistant-shell {
        width: 100%;
        min-height: 100vh;
        background: #ffffff;
    }
    .assistant-topbar {
        background: #2d7be8;
        color: #ffffff;
        text-align: center;
        font-size: 33px;
        font-weight: 700;
        line-height: 1.2;
        padding: 14px 20px 15px;
        position: relative;
    }
    .assistant-burger {
        display: none;
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        width: 42px;
        height: 42px;
        border: 0;
        border-radius: 10px;
        background: rgba(255,255,255,0.16);
        color: #ffffff;
        cursor: pointer;
        align-items: center;
        justify-content: center;
    }
    .assistant-burger svg { width: 24px; height: 24px; }
    .assistant-mobile-overlay { display: none; }
    .assistant-body {
        display: flex;
        align-items: stretch;
    }
    .assistant-sidebar {
        width: 250px;
        background: #ffffff;
        border-right: 2px solid #cfcfcf;
        padding: 10px 8px 18px;
        display: flex;
        flex-direction: column;
    }
    .assistant-user {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 2px;
        margin-bottom: 34px;
    }
    .assistant-avatar {
        width: 70px;
        height: 70px;
        border: 4px solid #111111;
        border-radius: 999px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #ffffff;
    }
    .assistant-meta {
        margin-top: 10px;
        text-align: center;
        font-size: 11px;
        line-height: 1.2;
        font-weight: 700;
        color: #666666;
        max-width: 150px;
        word-break: break-word;
    }
    .assistant-nav {
        display: flex;
        flex-direction: column;
        gap: 20px;
        padding: 0 4px;
    }
    .assistant-sidebar-bottom {
        margin-top: auto;
        padding: 18px 4px 0;
    }
    .assistant-nav-item {
        display: flex;
        align-items: center;
        gap: 12px;
        min-height: 44px;
        border-radius: 14px;
        background: #eeeeee;
        padding: 0 14px;
        color: #1c1c1c;
        font-size: 21px;
        font-weight: 700;
        text-decoration: none;
        transition: background 0.2s ease, transform 0.2s ease;
    }
    .assistant-nav-item:hover {
        background: #e7e7e7;
        transform: translateX(2px);
    }
    .assistant-nav-item.active {
        background: #e3e3e3;
    }
    .assistant-nav-item svg {
        width: 26px;
        height: 26px;
        flex-shrink: 0;
    }
    .assistant-logout-btn {
        width: 100%;
        border: 0;
        cursor: pointer;
        text-align: left;
        font-family: Arial, Helvetica, sans-serif;
    }
    .assistant-main {
        flex: 1;
        background: #ffffff;
        padding: 20px 26px 26px;
    }
    .hero-banner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        border-radius: 18px;
        background: #d9d9d9;
        padding: 18px 16px 10px 26px;
        margin: 14px 0 34px;
        overflow: hidden;
        min-height: 170px;
    }
    .hero-title {
        margin: 0 0 6px;
        color: #111111;
        font-size: 38px;
        font-weight: 700;
        line-height: 1.05;
    }
    .hero-subtitle {
        margin: 0;
        color: #111111;
        font-size: 15px;
        font-weight: 500;
    }
    .hero-illustration {
        width: 290px;
        max-width: 38%;
        display: block;
        object-fit: contain;
        object-position: right bottom;
        align-self: flex-end;
        margin-right: -8px;
        margin-bottom: -10px;
    }
    .chart-card {
        max-width: 860px;
        border-radius: 20px;
        background: #d9d9d9;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.22);
        padding: 16px 20px 12px;
    }
    .chart-title {
        margin: 0 0 10px;
        color: #111111;
        font-size: 16px;
        font-weight: 700;
    }
    .chart-holder {
        height: 330px;
    }
    .logout-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.34);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 999;
    }
    .logout-overlay.active {
        display: flex;
    }
    .logout-modal {
        width: 100%;
        max-width: 230px;
        border-radius: 16px;
        background: #ffffff;
        padding: 12px 10px 10px;
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.18);
    }
    .logout-modal-title {
        color: #111111;
        font-size: 14px;
        font-weight: 700;
        margin: 0 0 4px;
    }
    .logout-modal-text {
        color: #c7c7c7;
        font-size: 10px;
        font-weight: 600;
        margin: 0 0 12px;
    }
    .logout-modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 6px;
    }
    .logout-modal-btn {
        border: 0;
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        line-height: 1;
    }
    .logout-cancel  { background: #f2b705; color: #111111; }
    .logout-confirm { background: #2d7be8; color: #ffffff; }

    @media (max-width: 900px) {
        .assistant-burger { display: inline-flex; }
        .assistant-mobile-overlay.active {
            display: block;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.28);
            z-index: 998;
        }
        .assistant-sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: min(280px, 84vw);
            transform: translateX(-100%);
            transition: transform 0.25s ease;
            z-index: 999;
            overflow-y: auto;
        }
        .assistant-sidebar.mobile-open { transform: translateX(0); }
        .assistant-main { padding: 16px; }
    }
</style>

<div class="assistant-page">
    <div class="assistant-shell">

        <div class="assistant-topbar">
            <button type="button" class="assistant-burger" data-sidebar-open>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4 6h16v2H4V6zm0 5h16v2H4v-2zm0 5h16v2H4v-2z"/></svg>
            </button>
            Dashboard Asisten
        </div>

        <div class="assistant-body">
            <aside class="assistant-sidebar" id="mobileSidebar">
                <div class="assistant-user">
                    <div class="assistant-avatar">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:38px;height:38px;color:#111">
                            <path d="M12 12a5 5 0 100-10 5 5 0 000 10zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z"/>
                        </svg>
                    </div>
                    <div class="assistant-meta">
                        <div>{{ $user->displayName() }}</div>
                        <div>{{ $user->gender ?? 'Gender' }}</div>
                        <div>{{ $user->email }}</div>
                        <div>Asisten</div>
                    </div>
                </div>

                <nav class="assistant-nav">
                    <a href="{{ route('profile') }}" class="assistant-nav-item">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a5 5 0 100-10 5 5 0 000 10zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z"/></svg>
                        <span>Profil</span>
                    </a>
                    <a href="{{ route('asisten.dashboard') }}" class="assistant-nav-item active">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3l9 8h-3v10h-5v-6H11v6H6V11H3l9-8z"/></svg>
                        <span>Home</span>
                    </a>
                    <a href="{{ route('schedules.index') }}" class="assistant-nav-item">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M7 2h2v2h6V2h2v2h3v18H4V4h3V2zm11 8H6v10h12V10zM8 12h4v4H8v-4z"/></svg>
                        <span>Jadwal</span>
                    </a>
                    <a href="{{ route('histories.index') }}" class="assistant-nav-item">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M5 4h14v2H5V4zm0 4h14v12H5V8zm2 2v8h10v-8H7z"/></svg>
                        <span>Riwayat Aset</span>
                    </a>
                </nav>

                <div class="assistant-sidebar-bottom">
                    <button type="button" class="assistant-nav-item assistant-logout-btn" data-open-logout>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12H3m0 0l4-4m-4 4l4 4m5-9h5a2 2 0 012 2v6a2 2 0 01-2 2h-5"/></svg>
                        <span>Logout</span>
                    </button>
                </div>
            </aside>

            <main class="assistant-main">
                {{-- Hero Banner --}}
                <section class="hero-banner">
                    <div>
                        <h2 class="hero-title">Hi, {{ $user->displayName() }}</h2>
                        <p class="hero-subtitle">Track Maintenance Activity And Support Tasks</p>
                    </div>
                    <img class="hero-illustration" src="{{ asset('images/image1.png') }}" alt="Illustration">
                </section>

                {{-- Monthly Chart --}}
                <section class="chart-card">
                    <h3 class="chart-title">Jumlah Penanganan Setiap Bulan</h3>
                    <div class="chart-holder">
                        <canvas id="asistenDashboardChart"></canvas>
                    </div>
                </section>
            </main>
        </div>

    </div>
</div>

<div class="assistant-mobile-overlay" id="mobileSidebarOverlay"></div>

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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('asistenDashboardChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [{
                data: @json(array_values($monthlyData)),
                backgroundColor: '#56c3ff',
                borderWidth: 0,
                barThickness: 22
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#111111', font: { size: 11, weight: '700' } },
                    border: { color: '#444444' }
                },
                y: {
                    beginAtZero: true,
                    suggestedMax: 10,
                    ticks: { stepSize: 1, color: '#111111', font: { size: 11 } },
                    grid: { color: '#6f6f6f' },
                    border: { color: '#444444' }
                }
            }
        }
    });

    const overlay       = document.getElementById('logoutOverlay');
    const cancelBtn     = document.getElementById('logoutCancel');
    const mobileSidebar = document.getElementById('mobileSidebar');
    const mobileOverlay = document.getElementById('mobileSidebarOverlay');
    const sidebarOpen   = document.querySelector('[data-sidebar-open]');

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
    document.querySelectorAll('[data-open-logout]').forEach(function (button) {
        button.addEventListener('click', function () {
            overlay.classList.add('active');
        });
    });
    cancelBtn.addEventListener('click', function () {
        overlay.classList.remove('active');
    });
    overlay.addEventListener('click', function (event) {
        if (event.target === overlay) overlay.classList.remove('active');
    });
});
</script>
@endsection