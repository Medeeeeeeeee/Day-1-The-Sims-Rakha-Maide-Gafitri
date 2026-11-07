@extends('layouts.app')

@section('content')
<style>
    .lab-form-page {
        min-height: 100vh;
        background: #f2f2f2;
        padding: 0;
        font-family: Arial, Helvetica, sans-serif;
    }

    .lab-form-shell {
        width: 100%;
        min-height: 100vh;
        background: #ffffff;
    }

    .lab-form-header {
        background: #2d7be8;
        color: #ffffff;
        text-align: center;
        font-size: 33px;
        font-weight: 500;
        line-height: 1.2;
        padding: 10px 20px 12px;
    }

    .lab-form-body {
        padding: 14px 20px 24px;
    }

    .lab-form-grid {
        display: grid;
        grid-template-columns: 1.15fr 1fr;
        gap: 34px 48px;
        align-items: start;
    }

    .lab-field {
        margin-bottom: 10px;
    }

    .lab-label {
        display: block;
        color: #111111;
        font-size: 27px;
        font-weight: 500;
        line-height: 1.2;
        margin-bottom: 8px;
    }

    .lab-input,
    .lab-textarea {
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

    .lab-input {
        height: 76px;
        padding: 0 18px;
    }

    .lab-textarea {
        min-height: 146px;
        resize: none;
        padding: 12px 18px;
        line-height: 1.6;
    }

    .lab-input::placeholder,
    .lab-textarea::placeholder {
        color: #c6c6c6;
    }

    .lab-errors {
        margin-bottom: 16px;
        border-radius: 12px;
        border: 1px solid #fecaca;
        background: #fef2f2;
        color: #991b1b;
        padding: 12px 14px;
        font-size: 14px;
        font-weight: 600;
    }

    .lab-bottom {
        display: flex;
        align-items: flex-end;
        gap: 26px;
        margin-top: 6px;
    }

    .lab-submit {
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
    }

    .lab-submit:hover {
        background: #0fb000;
    }

    .lab-cancel {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 180px;
        height: 50px;
        border-radius: 16px;
        border: 1.8px solid #d0d0d0;
        background: #ffffff;
        color: #555555;
        font-size: 22px;
        font-weight: 500;
        text-decoration: none;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.08);
    }

    .lab-hint {
        margin-top: 8px;
        color: #666666;
        font-size: 13px;
    }

    @media (max-width: 900px) {
        .lab-form-header { font-size: 28px; }
        .lab-form-grid { grid-template-columns: 1fr; gap: 18px; }
        .lab-label { font-size: 22px; }
        .lab-input { height: 64px; font-size: 22px; }
        .lab-textarea { font-size: 22px; }
        .lab-bottom { flex-direction: column; align-items: stretch; }
        .lab-submit, .lab-cancel { width: 100%; min-width: 0; }
    }
</style>

<div class="lab-form-page">
    <div class="lab-form-shell">
        <div class="lab-form-header">Form Tambah Lab</div>

        <div class="lab-form-body">
            @if($errors->any())
            <div class="lab-errors">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
            @endif

            <form action="{{ route('labs.store') }}" method="POST">
                @csrf

                <div class="lab-form-grid">
                    <div>
                        <div class="lab-field">
                            <label class="lab-label" for="nama_lab">Nomor Lab</label>
                            <input
                                id="nama_lab"
                                class="lab-input"
                                type="text"
                                name="nama_lab"
                                value="{{ old('nama_lab') }}"
                                placeholder="Contoh: R.01"
                                required
                            >
                        </div>

                        <div class="lab-field">
                            <label class="lab-label" for="catatan">Deskripsi</label>
                            <textarea
                                id="catatan"
                                class="lab-textarea"
                                name="catatan_preview"
                                placeholder="Tambahkan deskripsi singkat&#10;untuk laboratorium ini."
                            >{{ old('catatan_preview') }}</textarea>
                        </div>
                    </div>

                    <div>
                        <div class="lab-field">
                            <label class="lab-label" for="pc_count">Jumlah PC</label>
                            <input
                                id="pc_count"
                                class="lab-input"
                                type="number"
                                name="pc_count"
                                value="{{ old('pc_count') }}"
                                min="1"
                                placeholder="30"
                                required
                            >
                            <div class="lab-hint">Masukkan jumlah perangkat PC yang tersedia di laboratorium.</div>
                        </div>
                    </div>
                </div>

                <div class="lab-bottom">
                    <a href="{{ route('labs.index') }}" class="lab-cancel">Batal</a>
                    <button type="submit" class="lab-submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
