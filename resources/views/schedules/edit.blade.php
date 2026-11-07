@extends('layouts.app')

@section('content')
<style>
    .schedule-form-page { min-height: 100vh; background: #f2f2f2; padding: 0; font-family: Arial, Helvetica, sans-serif; }
    .schedule-form-shell { width: 100%; min-height: 100vh; background: #ffffff; }
    .schedule-form-header { background: #2d7be8; color: #ffffff; text-align: center; font-size: 33px; font-weight: 500; line-height: 1.2; padding: 10px 20px 12px; }
    .schedule-form-body { padding: 14px 20px 24px; }
    .schedule-errors { margin-bottom: 16px; border-radius: 12px; border: 1px solid #fecaca; background: #fef2f2; color: #991b1b; padding: 12px 14px; font-size: 14px; font-weight: 600; }
    .schedule-grid { display: grid; grid-template-columns: 1.15fr 1fr; gap: 34px 48px; align-items: start; }
    .schedule-field { margin-bottom: 10px; }
    .schedule-label { display: block; color: #111111; font-size: 27px; font-weight: 500; line-height: 1.2; margin-bottom: 8px; }
    .schedule-input, .schedule-select, .schedule-textarea { width: 100%; border: 1.8px solid #d0d0d0; border-radius: 16px; background: #ffffff; box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16); color: #111111; font-size: 25px; font-family: Arial, Helvetica, sans-serif; outline: none; }
    .schedule-input, .schedule-select { height: 76px; padding: 0 18px; }
    .schedule-textarea { min-height: 146px; resize: none; padding: 12px 18px; line-height: 1.6; }
    .schedule-bottom { display: flex; align-items: flex-end; gap: 26px; margin-top: 6px; }
    .schedule-submit { display: inline-flex; align-items: center; justify-content: center; min-width: 224px; height: 50px; border: 0; border-radius: 16px; background: #12c100; color: #ffffff; font-size: 26px; font-weight: 500; cursor: pointer; }
    .schedule-cancel { display: inline-flex; align-items: center; justify-content: center; min-width: 180px; height: 50px; border-radius: 16px; border: 1.8px solid #d0d0d0; background: #ffffff; color: #555555; font-size: 22px; font-weight: 500; text-decoration: none; box-shadow: 0 3px 6px rgba(0, 0, 0, 0.08); }
    @media (max-width: 900px) {
        .schedule-form-header { font-size: 28px; }
        .schedule-grid { grid-template-columns: 1fr; gap: 18px; }
        .schedule-label { font-size: 22px; }
        .schedule-input, .schedule-select { height: 64px; font-size: 22px; }
        .schedule-textarea { font-size: 22px; }
        .schedule-bottom { flex-direction: column; align-items: stretch; }
        .schedule-submit, .schedule-cancel { width: 100%; min-width: 0; }
    }
</style>

<div class="schedule-form-page">
    <div class="schedule-form-shell">
        <div class="schedule-form-header">Form Edit Jadwal Maintenance</div>

        <div class="schedule-form-body">
            @if($errors->any())
            <div class="schedule-errors">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('schedules.update', $schedule) }}">
                @csrf
                @method('PUT')

                <div class="schedule-grid">
                    <div>
                        <div class="schedule-field">
                            <label class="schedule-label" for="keterangan">Judul Maintenance</label>
                            <input id="keterangan" class="schedule-input" type="text" name="keterangan" value="{{ old('keterangan', $schedule->keterangan) }}" placeholder="Contoh: Maintenance Lab Pagi">
                        </div>

                        <div class="schedule-field">
                            <label class="schedule-label" for="tanggal_jadwal">Tanggal Maintenance</label>
                            <input id="tanggal_jadwal" class="schedule-input" type="datetime-local" name="tanggal_jadwal" value="{{ old('tanggal_jadwal', \Illuminate\Support\Carbon::parse($schedule->tanggal_jadwal)->format('Y-m-d\TH:i')) }}" required>
                        </div>
                    </div>

                    <div>
                        <div class="schedule-field">
                            <label class="schedule-label" for="lab_id">Lab</label>
                            <select id="lab_id" class="schedule-select" name="lab_id" required>
                                @foreach($labs as $lab)
                                <option value="{{ $lab->id }}" {{ old('lab_id', $schedule->lab_id) == $lab->id ? 'selected' : '' }}>{{ $lab->nama_lab }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="schedule-field">
                            <label class="schedule-label" for="asisten_id">Asisten</label>
                            <select id="asisten_id" class="schedule-select" name="asisten_id" required>
                                @foreach($assistants as $assistant)
                                <option value="{{ $assistant->id }}" {{ old('asisten_id', $schedule->asisten_id) == $assistant->id ? 'selected' : '' }}>
                                    {{ $assistant->displayName() ?: $assistant->email }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="schedule-field">
                            <label class="schedule-label" for="status_jadwal">Status</label>
                            <select id="status_jadwal" class="schedule-select" name="status_jadwal">
                                <option value="menunggu" {{ old('status_jadwal', $schedule->status_jadwal) === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="selesai" {{ old('status_jadwal', $schedule->status_jadwal) === 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="schedule-bottom">
                    <a href="{{ route('schedules.index') }}" class="schedule-cancel">Batal</a>
                    <button type="submit" class="schedule-submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
