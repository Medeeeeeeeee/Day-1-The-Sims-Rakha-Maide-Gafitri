@extends('layouts.app')

@section('content')
<style>
    .ticket-form-page {
        min-height: 100vh;
        background: #f2f2f2;
        padding: 0;
        font-family: Arial, Helvetica, sans-serif;
    }

    .ticket-form-shell {
        width: 100%;
        min-height: 100vh;
        background: #ffffff;
    }

    .ticket-form-header {
        background: #2d7be8;
        color: #ffffff;
        text-align: center;
        font-size: 33px;
        font-weight: 500;
        line-height: 1.2;
        padding: 10px 20px 12px;
    }

    .ticket-form-body {
        padding: 14px 20px 24px;
    }

    .ticket-form-grid {
        display: grid;
        grid-template-columns: 1.15fr 1fr;
        gap: 34px 48px;
        align-items: start;
    }

    .ticket-field {
        margin-bottom: 10px;
    }

    .ticket-label {
        display: block;
        color: #111111;
        font-size: 27px;
        font-weight: 500;
        line-height: 1.2;
        margin-bottom: 8px;
    }

    .ticket-input,
    .ticket-select,
    .ticket-textarea {
        width: 100%;
        border: 1.8px solid #d0d0d0;
        border-radius: 16px;
        background: #ffffff;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16);
        color: #111111;
        font-size: 25px;
        font-family: Arial, Helvetica, sans-serif;
        outline: none;
    }

    .ticket-input,
    .ticket-select {
        height: 76px;
        padding: 0 18px;
    }

    .ticket-textarea {
        min-height: 146px;
        resize: none;
        padding: 12px 18px;
        line-height: 1.6;
    }

    .ticket-input::placeholder,
    .ticket-textarea::placeholder {
        color: #c6c6c6;
    }

    .ticket-select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='30' height='18' viewBox='0 0 30 18' fill='none'%3E%3Cpath d='M3 3L15 15L27 3' stroke='black' stroke-width='4.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 18px center;
        padding-right: 64px;
    }

    .ticket-errors {
        margin-bottom: 16px;
        border-radius: 12px;
        border: 1px solid #fecaca;
        background: #fef2f2;
        color: #991b1b;
        padding: 12px 14px;
        font-size: 14px;
        font-weight: 600;
    }

    .ticket-bottom {
        display: flex;
        align-items: flex-end;
        gap: 26px;
        margin-top: 6px;
    }

    .ticket-room {
        width: 186px;
        flex: 0 0 186px;
    }

    .ticket-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 224px;
        height: 50px;
        border: 0;
        border-radius: 16px;
        background: #12c100;
        color: #ffffff;
        font-size: 26px;
        font-weight: 500;
        cursor: pointer;
        box-shadow: none;
    }

    .ticket-submit:hover {
        background: #0fb000;
    }

    .ticket-hint {
        margin-top: 8px;
        color: #666666;
        font-size: 13px;
    }

    @media (max-width: 900px) {
        .ticket-form-header {
            font-size: 28px;
        }

        .ticket-form-grid {
            grid-template-columns: 1fr;
            gap: 18px;
        }

        .ticket-label {
            font-size: 22px;
        }

        .ticket-input,
        .ticket-select {
            height: 64px;
            font-size: 22px;
        }

        .ticket-textarea {
            font-size: 22px;
        }

        .ticket-bottom {
            flex-direction: column;
            align-items: stretch;
        }

        .ticket-room {
            width: 100%;
            flex-basis: auto;
        }

        .ticket-submit {
            width: 100%;
            min-width: 0;
        }
    }
</style>

<div class="ticket-form-page">
    <div class="ticket-form-shell">
        <div class="ticket-form-header">Form Ticket</div>

        <div class="ticket-form-body">
            @if($errors->any())
            <div class="ticket-errors">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
            @endif

            <form action="{{ route('tickets.store') }}" method="POST">
                @csrf

                <div class="ticket-form-grid">
                    <div>
                        <div class="ticket-field">
                            <label class="ticket-label" for="judul">Nama Pelapor</label>
                            <input
                                id="nama_pelapor"
                                class="ticket-input"
                                type="text"
                                name="nama_pelapor"
                                value="{{ old('nama_pelapor') }}"
                                
                                required
                            >
                        </div>

                        <div class="ticket-field">
                            <label class="ticket-label" for="judul">Judul Masalah</label>
                            <input
                                id="judul"
                                class="ticket-input"
                                type="text"
                                name="judul"
                                value="{{ old('judul') }}"
                                placeholder="Komputer Mati Total"
                                required
                            >
                        </div>

                        <div class="ticket-field">
                            <label class="ticket-label" for="deskripsi">Deskripsi</label>
                            <textarea
                                id="deskripsi"
                                class="ticket-textarea"
                                name="deskripsi"
                                placeholder="PC tidak bisa dinyalakan&#10;sejak kemarin."
                                required
                            >{{ old('deskripsi') }}</textarea>
                        </div>

                        <div class="ticket-field">
                            <label class="ticket-label" for="lab_id_mobile">Ruangan</label>
                            <div class="ticket-bottom">
                                <div class="ticket-room">
                                    <select id="lab_id_mobile" name="lab_id" class="ticket-select" required>
                                        <option value="">Pilih</option>
                                        @foreach($labs as $lab)
                                            <option value="{{ $lab->id }}" data-pc-count="{{ $lab->pc_count }}" {{ old('lab_id') == $lab->id ? 'selected' : '' }}>
                                                {{ $lab->nama_lab }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="ticket-submit">Submit</button>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="ticket-field">
                            <label class="ticket-label" for="nomor_pc">Aset (PC-ID)</label>
                            <select id="nomor_pc" name="nomor_pc" class="ticket-select" required>
                                <option value="">Pilih PC</option>
                            </select>
                            <div class="ticket-hint">Pilih ruangan terlebih dahulu untuk menampilkan daftar PC.</div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const labSelect = document.getElementById('lab_id_mobile');
    const pcSelect = document.getElementById('nomor_pc');

    function populatePcOptions() {
        const selectedLab = labSelect.options[labSelect.selectedIndex];
        const pcCount = selectedLab ? parseInt(selectedLab.dataset.pcCount || '0', 10) : 0;
        const oldPc = '{{ old("nomor_pc") ?: "" }}';

        pcSelect.innerHTML = '<option value="">Pilih PC</option>';

        if (!pcCount || Number.isNaN(pcCount)) {
            return;
        }

        for (let i = 1; i <= pcCount; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.text = 'PC-' + String(i).padStart(2, '0');

            if (oldPc && String(i) === oldPc) {
                option.selected = true;
            }

            pcSelect.appendChild(option);
        }
    }

    labSelect.addEventListener('change', populatePcOptions);
    window.addEventListener('DOMContentLoaded', populatePcOptions);
</script>
@endsection
