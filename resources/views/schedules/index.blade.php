@extends('layouts.app')

@section('content')
<style>
    .schedule-page { min-height: 100vh; background: #eef3f8; font-family: Arial, Helvetica, sans-serif; }
    .schedule-shell { display: flex; min-height: 100vh; background: #fff; }
    .schedule-sidebar { width: 275px; background: #fff; border-right: 2px solid #d3d8df; padding: 14px 12px 18px; }
    .schedule-profile { display: flex; flex-direction: column; align-items: center; padding: 6px 8px 18px; }
    .schedule-avatar { width: 88px; height: 88px; border: 4px solid #111; border-radius: 999px; display: flex; align-items: center; justify-content: center; background: #fff; }
    .schedule-avatar svg { width: 46px; height: 46px; color: #111; }
    .schedule-meta { margin-top: 10px; text-align: center; color: #4b5563; font-size: 14px; line-height: 1.25; font-weight: 600; }
    .schedule-nav { display: flex; flex-direction: column; gap: 22px; padding: 8px 0 0; }
    .schedule-nav-item { display: flex; align-items: center; gap: 14px; min-height: 46px; border-radius: 16px; padding: 0 16px; color: #111827; background: #ececec; text-decoration: none; font-size: 18px; font-weight: 700; }
    .schedule-nav-item.active { background: #ececec; box-shadow: inset 0 0 0 2px #d7eefb; }
    .schedule-nav-item svg { width: 28px; height: 28px; flex-shrink: 0; }
    .schedule-sidebar-bottom { padding-top: 22px; }
    .schedule-logout-btn { width: 100%; border: 0; cursor: pointer; text-align: left; font-family: Arial, Helvetica, sans-serif; }
    .schedule-main { flex: 1; padding: 0; }
    .schedule-topbar { background: #2d7be8; color: #fff; text-align: center; font-size: 34px; font-weight: 700; padding: 16px; }
    .schedule-content { padding: 22px; }
    .schedule-alert { margin-bottom: 16px; border: 1px solid #b7e3c4; background: #effbf2; color: #1d6b35; border-radius: 10px; padding: 10px 12px; font-size: 14px; font-weight: 600; }
    .schedule-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; margin-bottom: 18px; }
    .schedule-head h2 { margin: 0; color: #101828; font-size: 30px; }
    .schedule-head p { margin: 8px 0 0; color: #667085; font-size: 14px; }
    .schedule-btn { display: inline-flex; align-items: center; gap: 8px; background: #2d7be8; color: #fff; text-decoration: none; border-radius: 14px; padding: 12px 16px; font-size: 18px; font-weight: 700; box-shadow: 0 10px 20px rgba(45,123,232,.2); }
    .schedule-filter { display: grid; grid-template-columns: 1.3fr 1fr auto; gap: 14px; padding: 18px; background: #fff; border: 1px solid #d7e4f3; border-radius: 24px; box-shadow: 0 12px 25px rgba(16,24,40,.06); margin-bottom: 18px; }
    .schedule-field label { display: block; color: #344054; font-size: 14px; font-weight: 700; margin-bottom: 8px; }
    .schedule-field select, .schedule-field input { width: 100%; height: 48px; border: 1px solid #cbd5e1; border-radius: 14px; padding: 0 14px; font-size: 15px; }
    .schedule-submit { height: 48px; border: 0; border-radius: 14px; padding: 0 18px; background: #0e9f6e; color: #fff; font-weight: 700; cursor: pointer; }
    .schedule-cards { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; margin-bottom: 18px; }
    .schedule-card { border-radius: 22px; padding: 18px; color: #fff; display: flex; justify-content: space-between; gap: 12px; box-shadow: 0 12px 25px rgba(16,24,40,.12); }
    .schedule-card h3 { margin: 0; font-size: 15px; line-height: 1.4; }
    .schedule-card strong { display: block; margin-top: 10px; font-size: 42px; line-height: 1; }
    .card-orange { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .card-blue { background: linear-gradient(135deg, #3b82f6, #2563eb); }
    .card-green { background: linear-gradient(135deg, #22c55e, #16a34a); }
    .schedule-board { background: #fff; border: 1px solid #d7e4f3; border-radius: 28px; box-shadow: 0 14px 28px rgba(16,24,40,.08); overflow: hidden; margin-bottom: 22px; }
    .schedule-board-top { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 18px 22px; background: #f7fbff; border-bottom: 1px solid #d7e4f3; }
    .schedule-board-top h3 { margin: 0; color: #172b4d; font-size: 25px; }
    .schedule-board-top p { margin: 6px 0 0; color: #667085; font-size: 13px; }
    .schedule-legend { display: flex; gap: 12px; flex-wrap: wrap; color: #667085; font-size: 12px; font-weight: 700; }
    .schedule-legend span { display: inline-flex; align-items: center; gap: 6px; }
    .schedule-legend i { width: 10px; height: 10px; border-radius: 999px; display: inline-block; }
    .legend-menunggu { background: #f59e0b; }
    .legend-selesai { background: #22c55e; }
    .schedule-calendar { padding: 18px 22px 22px; }
    .schedule-weekdays, .schedule-week { display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); gap: 10px; }
    .schedule-weekdays { margin-bottom: 10px; }
    .schedule-weekday { text-align: center; color: #667085; font-size: 12px; font-weight: 700; text-transform: uppercase; }
    .schedule-week { margin-bottom: 10px; }
    .schedule-day { min-height: 128px; background: #f8fbff; border: 1px solid #d8e4f0; border-radius: 20px; padding: 12px; display: flex; flex-direction: column; gap: 8px; }
    .schedule-day.outside { opacity: .5; background: #f3f4f6; }
    .schedule-day.today { border-color: #3b82f6; box-shadow: inset 0 0 0 1px #93c5fd; }
    .schedule-day-top { display: flex; align-items: center; justify-content: space-between; gap: 8px; }
    .schedule-day-number { font-size: 24px; font-weight: 700; color: #172b4d; }
    .schedule-day-count { min-width: 28px; height: 28px; border-radius: 999px; background: #dbeafe; color: #1d4ed8; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; }
    .schedule-day-list { display: flex; flex-direction: column; gap: 7px; }
    .schedule-item { border-radius: 14px; background: #fff; border: 1px solid #e5e7eb; padding: 8px 9px; box-shadow: 0 4px 10px rgba(15,23,42,.05); }
    .schedule-item.pending { border-left: 4px solid #f59e0b; }
    .schedule-item.done { border-left: 4px solid #22c55e; }
    .schedule-item-time { color: #475467; font-size: 11px; font-weight: 700; }
    .schedule-item-title { margin-top: 4px; color: #111827; font-size: 12px; font-weight: 700; line-height: 1.35; }
    .schedule-item-meta { margin-top: 3px; color: #667085; font-size: 11px; }
    .schedule-empty { margin-top: auto; color: #98a2b3; font-size: 12px; font-weight: 600; }
    .schedule-table-box { background: #fff; border: 1px solid #d7e4f3; border-radius: 24px; box-shadow: 0 12px 25px rgba(16,24,40,.06); padding: 18px; }
    .schedule-table-box h3 { margin: 0 0 14px; color: #101828; font-size: 22px; }
    .schedule-table-wrap { overflow-x: auto; }
    .schedule-table { width: 100%; border-collapse: collapse; }
    .schedule-table th { background: #edf5ff; color: #111827; text-align: left; font-size: 14px; font-weight: 700; padding: 10px 12px; }
    .schedule-table td { border-bottom: 1px solid #eef2f6; color: #1f2937; font-size: 13px; padding: 11px 12px; }
    .status-pill { display: inline-flex; align-items: center; justify-content: center; min-width: 96px; border-radius: 999px; padding: 5px 12px; color: #111; font-size: 12px; font-weight: 700; }
    .status-menunggu { background: #facc15; }
    .status-selesai { background: #86efac; }
    .schedule-actions { display: flex; gap: 10px; flex-wrap: wrap; }
    .schedule-actions a, .schedule-actions button { border: 0; background: transparent; padding: 0; color: #111827; font-size: 13px; font-weight: 700; cursor: pointer; text-decoration: none; }
    .schedule-complete-btn { border: 0; border-radius: 10px; background: #16a34a !important; color: #fff !important; padding: 7px 12px !important; font-size: 12px !important; }
    .schedule-complete-note { color: #16a34a; font-size: 12px; font-weight: 700; }
    .schedule-empty-box { border: 1px dashed #cbd5e1; border-radius: 16px; padding: 24px; text-align: center; color: #667085; }
    .logout-overlay { position: fixed; inset: 0; background: rgba(0,0,0,.34); display: none; align-items: center; justify-content: center; z-index: 999; }
    .logout-overlay.active { display: flex; }
    .logout-modal { width: 100%; max-width: 230px; border-radius: 16px; background: #fff; padding: 12px 10px 10px; box-shadow: 0 10px 24px rgba(0,0,0,.18); }
    .logout-modal-title { color: #111; font-size: 14px; font-weight: 700; margin: 0 0 4px; }
    .logout-modal-text { color: #c7c7c7; font-size: 10px; font-weight: 600; margin: 0 0 12px; }
    .logout-modal-actions { display: flex; justify-content: flex-end; gap: 6px; }
    .logout-modal-btn { border: 0; border-radius: 8px; padding: 6px 12px; font-size: 12px; font-weight: 700; cursor: pointer; line-height: 1; }
    .logout-cancel { background: #f2b705; color: #111; }
    .logout-confirm { background: #2d7be8; color: #fff; }
    @media (max-width: 1100px) {
        .schedule-filter, .schedule-cards, .schedule-weekdays, .schedule-week { grid-template-columns: 1fr; }
        .schedule-board-top, .schedule-head, .schedule-shell { display: block; }
        .schedule-sidebar { width: 100%; border-right: 0; border-bottom: 1px solid #d9e2ec; }
        .schedule-main { margin-top: 0; }
    }
</style>

<div class="schedule-page">
    <div class="schedule-topbar">{{ $user->role === 'supervisor' ? 'Daftar Jadwal' : 'Jadwal Asisten' }}</div>
    <div class="schedule-shell">
        <aside class="schedule-sidebar">
            <div class="schedule-profile">
                <div class="schedule-avatar">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12a5 5 0 100-10 5 5 0 000 10zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z"/>
                    </svg>
                </div>
                <div class="schedule-meta">
                    <div>{{ $user->displayName() }}</div>
                    <div>{{ $user->gender ?? 'Gender' }}</div>
                    <div>{{ $user->email ?? 'admin@example.com' }}</div>
                </div>
            </div>

            <nav class="schedule-nav">
                <a href="{{ route('profile') }}" class="schedule-nav-item">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12a5 5 0 100-10 5 5 0 000 10zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z"/>
                    </svg>
                    <span>Profil</span>
                </a>
                <a href="{{ $user->role === 'supervisor' ? route('admin.dashboard') : route('asisten.dashboard') }}" class="schedule-nav-item">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 3l9 8h-3v10h-5v-6H11v6H6V11H3l9-8z"/>
                    </svg>
                    <span>Home</span>
                </a>
                @if($user->role === 'supervisor')
                <a href="{{ route('labs.index') }}" class="schedule-nav-item">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 11H13V5h-2v6H5v2h6v6h2v-6h6v-2z"/>
                    </svg>
                    <span>Kelola Lab</span>
                </a>
                <a href="{{ route('admin.assistants.index') }}" class="schedule-nav-item">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M15 14c2.761 0 5 1.567 5 3.5V19h-7v-1.5c0-1.064.679-2.017 1.742-2.65A5.964 5.964 0 0115 14zm-8-2a4 4 0 110-8 4 4 0 010 8zm0 2c3.314 0 6 1.567 6 3.5V19H1v-1.5C1 15.567 3.686 14 7 14z"/>
                    </svg>
                    <span>Asisten</span>
                </a>
                @endif
                <a href="{{ route('schedules.index') }}" class="schedule-nav-item active">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M7 2h2v2h6V2h2v2h3v18H4V4h3V2zm11 8H6v10h12V10zM8 12h4v4H8v-4z"/>
                    </svg>
                    <span>Jadwal</span>
                </a>
                <a href="{{ route('histories.index') }}" class="schedule-nav-item">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M5 4h14v2H5V4zm0 4h14v12H5V8zm2 2v8h10v-8H7z"/>
                    </svg>
                    <span>Riwayat Aset</span>
                </a>
            </nav>
            <div class="schedule-sidebar-bottom">
                <button type="button" class="schedule-nav-item schedule-logout-btn" data-open-logout>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H3m0 0l4-4m-4 4l4 4m5-9h5a2 2 0 012 2v6a2 2 0 01-2 2h-5"/>
                    </svg>
                    <span>Logout</span>
                </button>
            </div>
        </aside>

        <main class="schedule-main">
            <div class="schedule-content">
                @if(session('success'))
                <div class="schedule-alert">{{ session('success') }}</div>
                @endif

                <div class="schedule-head">
                    <div>
                        <h2>Jadwal Maintenance Bulanan</h2>
                        <p>Pilih ruangan lab lalu lihat seluruh jadwal maintenance selama sebulan dalam bentuk kalender.</p>
                    </div>
                    @if($user->role === 'supervisor')
                    <a href="{{ route('schedules.create') }}" class="schedule-btn">+ Tambah Jadwal</a>
                    @endif
                </div>

                <form method="GET" action="{{ route('schedules.index') }}" class="schedule-filter">
                    <div class="schedule-field">
                        <label for="lab_id">Lab / Ruangan</label>
                        <select id="lab_id" name="lab_id">
                            @foreach($labs as $lab)
                            <option value="{{ $lab->id }}" {{ (string) $selectedLabId === (string) $lab->id ? 'selected' : '' }}>{{ $lab->nama_lab }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="schedule-field">
                        <label for="month">Bulan</label>
                        <input id="month" type="month" name="month" value="{{ $selectedMonth->format('Y-m') }}">
                    </div>
                    <button type="submit" class="schedule-submit">Tampilkan Jadwal</button>
                </form>

                <div class="schedule-cards">
                    <div class="schedule-card card-orange">
                        <div>
                            <h3>Lab/Ruangan Dalam Perbaikan</h3>
                            <strong>{{ $proses }}</strong>
                        </div>
                        <div>Lab</div>
                    </div>
                    <div class="schedule-card card-blue">
                        <div>
                            <h3>Total Jadwal Bulan Ini</h3>
                            <strong>{{ $total }}</strong>
                        </div>
                        <div>All</div>
                    </div>
                    <div class="schedule-card card-green">
                        <div>
                            <h3>Maintenance Selesai</h3>
                            <strong>{{ $selesai }}</strong>
                        </div>
                        <div>Done</div>
                    </div>
                </div>

                <section class="schedule-board">
                    <div class="schedule-board-top">
                        <div>
                            <h3>{{ $selectedLab?->nama_lab ?? 'Lab belum dipilih' }} - {{ $selectedMonth->translatedFormat('F Y') }}</h3>
                            <p>Kalender maintenance per bulan untuk ruangan yang dipilih.</p>
                        </div>
                        <div class="schedule-legend">
                            <span><i class="legend-menunggu"></i> Menunggu</span>
                            <span><i class="legend-selesai"></i> Selesai</span>
                        </div>
                    </div>

                    <div class="schedule-calendar">
                        <div class="schedule-weekdays">
                            <div class="schedule-weekday">Sen</div>
                            <div class="schedule-weekday">Sel</div>
                            <div class="schedule-weekday">Rab</div>
                            <div class="schedule-weekday">Kam</div>
                            <div class="schedule-weekday">Jum</div>
                            <div class="schedule-weekday">Sab</div>
                            <div class="schedule-weekday">Min</div>
                        </div>
                        @foreach($calendarWeeks as $week)
                        <div class="schedule-week">
                            @foreach($week as $day)
                            <div class="schedule-day {{ $day['in_month'] ? '' : 'outside' }} {{ $day['is_today'] ? 'today' : '' }}">
                                <div class="schedule-day-top">
                                    <div class="schedule-day-number">{{ $day['date']->format('d') }}</div>
                                    @if($day['items']->count() > 0)
                                    <div class="schedule-day-count">{{ $day['items']->count() }}</div>
                                    @endif
                                </div>

                                @if($day['items']->count() > 0)
                                <div class="schedule-day-list">
                                    @foreach($day['items'] as $item)
                                    <div class="schedule-item {{ $item->status_jadwal === 'selesai' ? 'done' : 'pending' }}">
                                        <div class="schedule-item-time">{{ \Illuminate\Support\Carbon::parse($item->tanggal_jadwal)->format('H:i') }}</div>
                                        <div class="schedule-item-title">{{ $item->keterangan ?: 'Maintenance rutin laboratorium' }}</div>
                                        <div class="schedule-item-meta">{{ $item->asisten?->displayName() ?: ($item->asisten->email ?? 'Belum ada asisten') }}</div>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="schedule-empty">Tidak ada maintenance</div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </section>

                <section class="schedule-table-box">
                    <h3>Daftar Jadwal Pada Bulan Ini</h3>
                    @if($schedules->isEmpty())
                    <div class="schedule-empty-box">
                        Belum ada jadwal maintenance untuk {{ $selectedLab?->nama_lab ?? 'lab yang dipilih' }} pada bulan {{ $selectedMonth->translatedFormat('F Y') }}.
                    </div>
                    @else
                    <div class="schedule-table-wrap">
                        <table class="schedule-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Lab</th>
                                    <th>Asisten</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    @if(in_array($user->role, ['supervisor', 'asisten'], true))
                                    <th>Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($schedules as $schedule)
                                <tr>
                                    <td>#{{ str_pad($schedule->id, 3, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $schedule->lab->nama_lab ?? '-' }}</td>
                                    <td>{{ $schedule->asisten?->displayName() ?: ($schedule->asisten->email ?? '-') }}</td>
                                    <td>{{ \Illuminate\Support\Carbon::parse($schedule->tanggal_jadwal)->format('d M Y H:i') }}</td>
                                    <td>{{ $schedule->keterangan ?: '-' }}</td>
                                    <td>
                                        <span class="status-pill {{ $schedule->status_jadwal === 'selesai' ? 'status-selesai' : 'status-menunggu' }}">
                                            {{ $schedule->status_jadwal === 'selesai' ? 'Selesai' : 'Menunggu' }}
                                        </span>
                                    </td>
                                    @if(in_array($user->role, ['supervisor', 'asisten'], true))
                                    <td>
                                        <div class="schedule-actions">
                                            @if($user->role === 'asisten')
                                                @if($schedule->status_jadwal !== 'selesai')
                                                <form action="{{ route('schedules.complete', $schedule) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="schedule-complete-btn">Selesai</button>
                                                </form>
                                                @else
                                                <span class="schedule-complete-note">Sudah selesai</span>
                                                @endif
                                            @endif

                                            @if($user->role === 'supervisor')
                                            <a href="{{ route('schedules.edit', $schedule) }}">Edit</a>
                                            <form action="{{ route('schedules.destroy', $schedule) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit">Hapus</button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </section>
            </div>
        </main>
    </div>
</div>

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

        openButtons.forEach((button) => {
            button.addEventListener('click', function () {
                overlay.classList.add('active');
            });
        });

        if (cancelBtn) {
            cancelBtn.addEventListener('click', function () {
                overlay.classList.remove('active');
            });
        }

        if (overlay) {
            overlay.addEventListener('click', function (event) {
                if (event.target === overlay) {
                    overlay.classList.remove('active');
                }
            });
        }
    });
</script>
@endsection
