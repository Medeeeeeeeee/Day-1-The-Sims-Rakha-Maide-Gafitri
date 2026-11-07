@extends('layouts.app')

@section('content')
<style>
    .student-dashboard {
        min-height: 100vh;
        background: #efefef;
        padding: 0;
        font-family: Arial, Helvetica, sans-serif;
    }

    .student-shell {
        width: 100%;
        margin: 0 auto;
        background: #ffffff;
        min-height: 100vh;
    }

    .student-topbar {
        background: #2d7be8;
        color: #ffffff;
        text-align: center;
        font-size: 33px;
        font-weight: 700;
        line-height: 1.2;
        padding: 14px 20px 15px;
        position: relative;
    }

    .student-burger {
        display: none;
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        width: 42px;
        height: 42px;
        border: 0;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.16);
        color: #ffffff;
        cursor: pointer;
        align-items: center;
        justify-content: center;
    }

    .student-burger svg {
        width: 24px;
        height: 24px;
    }

    .student-mobile-overlay {
        display: none;
    }

    .student-body {
        display: flex;
        align-items: stretch;
    }

    .student-sidebar {
        width: 250px;
        background: #ffffff;
        border-right: 2px solid #cfcfcf;
        padding: 10px 8px 18px;
        display: flex;
        flex-direction: column;
    }

    .student-profile {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 2px;
        margin-bottom: 30px;
    }

    .student-avatar {
        width: 70px;
        height: 70px;
        border: 4px solid #111111;
        border-radius: 999px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #ffffff;
    }

    .student-meta {
        margin-top: 8px;
        text-align: center;
        font-size: 11px;
        line-height: 1.15;
        font-weight: 700;
        color: #666666;
    }

    

    .student-sidebar-bottom {
        margin-top: auto;
        padding: 18px 4px 0;
    }

    .student-nav-item {
        display: flex;
        align-items: center;
        gap: 12px;
        min-height: 42px;
        border-radius: 14px;
        background: #eeeeee;
        padding: 0 14px;
        color: #1c1c1c;
        font-size: 22px;
        font-weight: 700;
        text-decoration: none;
    }

   

    .student-logout-btn {
        width: 100%;
        border: 0;
        cursor: pointer;
        text-align: left;
        font-family: Arial, Helvetica, sans-serif;
    }

    .student-main {
        flex: 1;
        background: #ffffff;
        padding: 18px 22px 16px;
    }

    .student-alert {
        margin-bottom: 12px;
        border: 1px solid #b7e3c4;
        background: #effbf2;
        color: #1d6b35;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 14px;
        font-weight: 600;
    }

    .student-hero {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 26px;
    }

    .student-hero-title {
        margin: 0;
        color: #111111;
        font-size: 30px;
        line-height: 1.2;
        font-weight: 700;
    }

    .student-ticket-btn {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        border-radius: 12px;
        background: #2d7be8;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.22);
        color: #ffffff;
        text-decoration: none;
        font-size: 24px;
        font-weight: 700;
        line-height: 1;
        padding: 10px 14px 11px;
        white-space: nowrap;
    }

    .student-ticket-btn .plus {
        font-size: 39px;
        line-height: 0.8;
        font-weight: 700;
        margin-right: 2px;
    }

    .student-stats-wrap {
        background: #d9d9d9;
        border-radius: 18px;
        padding: 16px 14px 18px;
        margin-bottom: 30px;
    }

    .student-stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    .student-card {
        background: #ffffff;
        border: 2px solid #838383;
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.18);
        padding: 10px 12px 8px;
    }

    .student-card-row {
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }

    .student-card-icon {
        width: 32px;
        height: 32px;
        color: #111111;
        flex-shrink: 0;
        object-fit: contain;
    }

    .student-card-check {
        width: 34px;
        height: 34px;
        border: 2px solid #2d2d2d;
        border-radius: 999px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .student-card-title {
        color: #141414;
        font-size: 15px;
        font-weight: 700;
        line-height: 1.1;
        margin-top: 1px;
    }

    .student-card-value {
        color: #111111;
        font-size: 16px;
        font-weight: 700;
        line-height: 1;
        margin-top: 5px;
    }

    .student-progress {
        width: 88px;
        height: 4px;
        border-radius: 999px;
        background: #e6e6e6;
        margin-top: 4px;
    }

    .student-progress > span {
        display: block;
        height: 4px;
        border-radius: 999px;
    }

    .progress-blue {
        background: #4aa8ff;
    }

    .progress-yellow {
        background: #efb028;
    }

    .progress-green {
        background: #47e238;
    }

    .student-section-title {
        margin: 0 0 8px;
        color: #111111;
        font-size: 21px;
        font-weight: 700;
        line-height: 1.2;
    }

    .student-table {
        width: 100%;
        border-collapse: collapse;
    }

    .student-table thead tr {
        background: #ebebeb;
    }

    .student-table th {
        color: #111111;
        text-align: left;
        font-size: 14px;
        font-weight: 700;
        padding: 4px 10px;
    }

    .student-table td {
        color: #202020;
        font-size: 13px;
        padding: 9px 10px;
        vertical-align: middle;
    }

    .student-table td:first-child {
        width: 9%;
        white-space: nowrap;
    }

    .student-table td:nth-child(2) {
        width: 43%;
    }

    .student-table td:nth-child(3) {
        width: 24%;
        white-space: nowrap;
    }

    .student-table td:nth-child(4) {
        width: 24%;
    }

    .ticket-id {
        color: #2b2b2b;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 98px;
        border-radius: 999px;
        color: #111111;
        font-size: 12px;
        font-weight: 700;
        padding: 4px 14px;
    }

    .status-proses {
        background: #eda400;
    }

    .status-selesai {
        background: #4cff2b;
    }

    .status-pending {
        background: #ff3030;
        color: #111111;
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

    .logout-cancel {
        background: #f2b705;
        color: #111111;
    }

    .logout-confirm {
        background: #2d7be8;
        color: #ffffff;
    }

    @media (max-width: 900px) {
        .student-burger {
            display: inline-flex;
        }

        .student-mobile-overlay.active {
            display: block;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.28);
            z-index: 998;
        }

        .student-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: min(280px, 84vw);
            border-right: 2px solid #cfcfcf;
            transform: translateX(-100%);
            transition: transform 0.25s ease;
            z-index: 999;
            overflow-y: auto;
        }

        .student-sidebar.mobile-open {
            transform: translateX(0);
        }

        .student-main {
            padding: 16px;
        }

        .student-hero {
            flex-direction: column;
            align-items: flex-start;
        }

        .student-stats {
            grid-template-columns: 1fr;
        }

        

        .student-hero-title {
            font-size: 24px;
        }
    }
</style>

<div class="student-dashboard">
    <div class="student-shell">
        <div class="student-topbar">
            <button type="button" class="student-burger" data-sidebar-open>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4 6h16v2H4V6zm0 5h16v2H4v-2zm0 5h16v2H4v-2z"/></svg>
            </button>
            Dashboard Mahasiswa
        </div>

        <div class="student-body">
            

            <main class="student-main">
                @if(session('success'))
                <div class="student-alert">{{ session('success') }}</div>
                @endif

                <div class="student-hero">
                    <h2 class="student-hero-title">Selamat Datang</h2>
                    <a href="{{ route('tickets.create') }}" class="student-ticket-btn">
                        <span class="plus">+</span>
                        <span>Buat Ticket</span>
                    </a>
                </div>

                <div class="student-stats-wrap">
                    <div class="student-stats">
                        <div class="student-card">
                            <div class="student-card-row">
                                <img class="student-card-icon" src="{{ asset('images/image.png') }}" alt="Ticket Dibuat">
                                <div>
                                    <div class="student-card-title">Ticket Dibuat</div>
                                    <div class="student-card-value">{{ $total }}</div>
                                    <div class="student-progress"><span class="progress-blue" style="width: {{ $pctTotal }}%"></span></div>
                                </div>
                            </div>
                        </div>

                        <div class="student-card">
                            <div class="student-card-row">
                                <img class="student-card-icon" src="{{ asset('images/image2.png') }}" alt="Diproses">
                                <div>
                                    <div class="student-card-title">Diproses</div>
                                    <div class="student-card-value">{{ $proses }}</div>
                                    <div class="student-progress"><span class="progress-yellow" style="width: {{ $pctProses }}%"></span></div>
                                </div>
                            </div>
                        </div>

                        <div class="student-card">
                            <div class="student-card-row">
                                <img class="student-card-icon" src="{{ asset('images/image3.png') }}" alt="Selesai">
                                <div>
                                    <div class="student-card-title">Selesai</div>
                                    <div class="student-card-value">{{ $selesai }}</div>
                                    <div class="student-progress"><span class="progress-green" style="width: {{ $pctSelesai }}%"></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <section>
                    <h3 class="student-section-title">Riwayat Ticket</h3>
                    <table class="student-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul Masalah</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTickets as $ticket)
                            <tr>
                                <td class="ticket-id">#{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $ticket->judul }}</td>
                                <td>{{ $ticket->created_at->format('d M Y') }}</td>
                                <td>
                                    @php
                                        $statusClass = $ticket->status === 'proses'
                                            ? 'status-proses'
                                            : ($ticket->status === 'selesai' ? 'status-selesai' : 'status-pending');
                                        $statusLabel = $ticket->status === 'proses'
                                            ? 'Diproses'
                                            : ($ticket->status === 'selesai' ? 'Selesai' : 'Pending');
                                    @endphp
                                    <span class="status-pill {{ $statusClass }}">{{ $statusLabel }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" style="padding:14px 10px;color:#666;font-size:13px;">Belum ada ticket.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
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
        const openButtons = document.querySelectorAll('[data-open-logout]');
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

        openButtons.forEach((button) => {
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
