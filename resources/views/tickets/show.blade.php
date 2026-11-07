@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Ticket #{{ $ticket->id }}</h1>
    <p><strong>Lab:</strong> {{ $ticket->lab->nama_lab ?? '-' }}</p>
    <p><strong>PC:</strong> {{ $ticket->nomor_pc }}</p>
    <p><strong>Title:</strong> {{ $ticket->judul }}</p>
    <p><strong>Description:</strong> {{ $ticket->deskripsi }}</p>
    <p><strong>Status:</strong> {{ $ticket->status }}</p>
    <p><strong>Reported:</strong> {{ $ticket->created_at }}</p>
    @auth
    <a href="{{ route('tickets.edit',$ticket) }}" class="text-blue-600">Edit</a>
    @endauth
</div>
@endsection