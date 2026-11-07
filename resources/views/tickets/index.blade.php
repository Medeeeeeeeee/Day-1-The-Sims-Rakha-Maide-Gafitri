@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Tickets</h1>
    @if(session('success'))
        <div class="text-green-600 mb-2">{{ session('success') }}</div>
    @endif
    <a href="{{ route('tickets.create') }}" class="mb-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">New Ticket</a>
    <table class="w-full border">
        <thead>
            <tr>
                <th class="border px-2">#</th>
                <th class="border px-2">Lab</th>
                <th class="border px-2">PC</th>
                <th class="border px-2">Title</th>
                <th class="border px-2">Status</th>
                <th class="border px-2">Reported</th>
                <th class="border px-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
            <tr>
                <td class="border px-2">{{ $ticket->id }}</td>
                <td class="border px-2">{{ $ticket->lab->nama_lab ?? '-' }}</td>
                <td class="border px-2">{{ $ticket->nomor_pc }}</td>
                <td class="border px-2"><a href="{{ route('tickets.show',$ticket) }}" class="underline">{{ $ticket->judul }}</a></td>
                <td class="border px-2">{{ $ticket->status }}</td>
                <td class="border px-2">{{ $ticket->created_at->format('Y-m-d') }}</td>
                <td class="border px-2">
                    @auth
                        <a href="{{ route('tickets.edit',$ticket) }}" class="text-blue-600">Edit</a>
                        <form action="{{ route('tickets.destroy',$ticket) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600" onclick="return confirm('Delete?')">Delete</button>
                        </form>
                    @endauth
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
