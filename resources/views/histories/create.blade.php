@extends('layouts.app')
@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">New Maintenance History</h1>
    <form method="POST" action="{{ route('histories.store') }}">
        @csrf
        <div class="mb-4">
            <label>Lab</label>
            <select name="lab_id" class="border w-full">
                @foreach($labs as $lab)
                <option value="{{ $lab->id }}">{{ $lab->nama_lab }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label>Ticket (optional)</label>
            <select name="ticket_id" class="border w-full">
                <option value="">-- none --</option>
                @foreach($tickets as $ticket)
                <option value="{{ $ticket->id }}">#{{ $ticket->id }} {{ $ticket->judul }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label>Tindakan</label>
            <textarea name="tindakan" class="border w-full" required></textarea>
        </div>
        <div class="mb-4">
            <label>Catatan</label>
            <textarea name="catatan" class="border w-full"></textarea>
        </div>
        <div class="mb-4">
            <label>Tanggal Perawatan</label>
            <input type="date" name="tanggal_perawatan" class="border w-full" required>
        </div>
        <button class="bg-green-600 text-white px-4 py-2 rounded">Save</button>
    </form>
</div>
@endsection
