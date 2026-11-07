@extends('layouts.app')
@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Edit Maintenance History</h1>
    <form method="POST" action="{{ route('histories.update',$history) }}">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label>Lab</label>
            <select name="lab_id" class="border w-full">
                @foreach($labs as $lab)
                <option value="{{ $lab->id }}" {{ $history->lab_id==$lab->id?'selected':'' }}>{{ $lab->nama_lab }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label>PC</label>
            <input type="number" name="nomor_pc" value="{{ $history->nomor_pc }}" class="border w-full" required>
        </div>
        <div class="mb-4">
            <label>Ticket (optional)</label>
            <select name="ticket_id" class="border w-full">
                <option value="">-- none --</option>
                @foreach($tickets as $ticket)
                <option value="{{ $ticket->id }}" {{ $history->ticket_id==$ticket->id?'selected':'' }}>#{{ $ticket->id }} {{ $ticket->judul }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label>Tindakan</label>
            <textarea name="tindakan" class="border w-full" required>{{ $history->tindakan }}</textarea>
        </div>
        <div class="mb-4">
            <label>Catatan</label>
            <textarea name="catatan" class="border w-full">{{ $history->catatan }}</textarea>
        </div>
        <div class="mb-4">
            <label>Tanggal Perawatan</label>
            <input type="date" name="tanggal_perawatan" value="{{ $history->tanggal_perawatan->format('Y-m-d') }}" class="border w-full" required>
        </div>
        <button class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
    </form>
</div>
@endsection