@extends('layouts.app')

@section('content')
<style>
    .response-page {
        min-height: 100vh;
        background: #f2f2f2;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px 12px;
        font-family: Arial, Helvetica, sans-serif;
    }

    .response-card {
        width: 100%;
        max-width: 460px;
        background: #ffffff;
        padding: 24px 18px 18px;
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.08);
    }

    .response-header {
        background: #2d7be8;
        color: #ffffff;
        text-align: center;
        font-size: 17px;
        font-weight: 500;
        padding: 10px 12px;
        margin-bottom: 16px;
    }

    .response-summary {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 12px;
    }

    .response-ticket-id {
        margin: 0;
        color: #111111;
        font-size: 16px;
        font-weight: 700;
    }

    .response-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 92px;
        border-radius: 999px;
        padding: 4px 12px;
        font-size: 12px;
        font-weight: 700;
        color: #111111;
    }

    .status-pending {
        background: #ff3030;
    }

    .status-proses {
        background: #eda400;
    }

    .status-selesai {
        background: #4cff2b;
    }

    .response-detail {
        margin-bottom: 14px;
        color: #222222;
        font-size: 15px;
        line-height: 1.55;
    }

    .response-detail strong {
        display: inline-block;
        min-width: 86px;
        color: #111111;
    }

    .response-field {
        margin-bottom: 12px;
    }

    .response-label {
        display: block;
        color: #111111;
        font-size: 15px;
        font-weight: 500;
        margin-bottom: 6px;
    }

    .response-input,
    .response-select {
        width: 100%;
        border: 1.5px solid #d0d0d0;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.12);
        font-family: Arial, Helvetica, sans-serif;
        outline: none;
    }

    .response-input {
        height: 44px;
        padding: 0 14px;
        font-size: 14px;
        color: #111111;
    }

    .response-input::placeholder {
        color: #c7c7c7;
    }

    .response-select {
        height: 42px;
        padding: 0 12px;
        font-size: 14px;
        color: #18c300;
        font-weight: 500;
        max-width: 180px;
    }

    .response-actions {
        display: flex;
        justify-content: center;
        gap: 12px;
        margin-top: 18px;
    }

    .response-btn {
        border: 0;
        border-radius: 12px;
        padding: 10px 20px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
    }

    .response-submit {
        background: #167de6;
        color: #ffffff;
        min-width: 160px;
    }

    .response-back {
        background: #eeeeee;
        color: #333333;
    }

    .response-errors {
        margin-bottom: 12px;
        border-radius: 12px;
        border: 1px solid #fecaca;
        background: #fef2f2;
        color: #991b1b;
        padding: 12px 14px;
        font-size: 13px;
        font-weight: 600;
    }
</style>

@php
    $statusClass = $ticket->status === 'proses'
        ? 'status-proses'
        : ($ticket->status === 'selesai' ? 'status-selesai' : 'status-pending');
    $statusLabel = $ticket->status === 'proses'
        ? 'Diproses'
        : ($ticket->status === 'selesai' ? 'Selesai' : 'Pending');
@endphp

<div class="response-page">
    <div class="response-card">
        <div class="response-header">Detail Ticket &amp; Respon</div>

        @if($errors->any())
        <div class="response-errors">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
        @endif

        <div class="response-summary">
            <p class="response-ticket-id">Ticket #{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}</p>
            <span class="response-status {{ $statusClass }}">{{ $statusLabel }}</span>
        </div>

        <div class="response-detail">
            <div><strong>Pengguna:</strong> {{ $ticket->nama_pelapor ?: 'Anon' }}</div>
            <div><strong>Masalah:</strong> {{ $ticket->judul }}</div>
            <div><strong>Deskripsi:</strong> {{ $ticket->deskripsi }}</div>
        </div>

        <form action="{{ route('tickets.update', $ticket) }}" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="lab_id" value="{{ old('lab_id', $ticket->lab_id) }}">
            <input type="hidden" name="nomor_pc" value="{{ old('nomor_pc', $ticket->nomor_pc) }}">
            <input type="hidden" name="nama_pelapor" value="{{ old('nama_pelapor', $ticket->nama_pelapor) }}">
            <input type="hidden" name="judul" value="{{ old('judul', $ticket->judul) }}">
            <input type="hidden" name="deskripsi" value="{{ old('deskripsi', $ticket->deskripsi) }}">

            <div class="response-field">
                <label class="response-label" for="tindakan">Tindakan</label>
                <input
                    id="tindakan"
                    class="response-input"
                    type="text"
                    name="tindakan"
                    value="{{ old('tindakan') }}"
                    placeholder="Sedang dicek PSU dan kabel."
                >
            </div>

            <div class="response-field">
                <label class="response-label" for="status">Ubah Status</label>
                <select id="status" class="response-select" name="status">
                    <option value="pending" {{ old('status', $ticket->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="proses" {{ old('status', $ticket->status) === 'proses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ old('status', $ticket->status) === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <div class="response-actions">
                <a href="{{ route('tickets.index') }}" class="response-btn response-back">Kembali</a>
                <button type="submit" class="response-btn response-submit">Kirim Respon</button>
            </div>
        </form>
    </div>
</div>
@endsection
