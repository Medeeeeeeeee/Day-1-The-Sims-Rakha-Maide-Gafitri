<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaintenanceHistory;
use App\Models\Lab;
use App\Models\Ticket;

class MaintenanceHistoryController extends Controller
{
    protected function buildHistoryQuery(Request $request)
    {
        $user = $request->user();
        $query = MaintenanceHistory::with(['lab', 'ticket', 'asisten'])->orderBy('tanggal_perawatan', 'desc');

        if ($user && $user->role === 'asisten') {
            $query->where('asisten_nim', $user->nim);
        }

        if ($request->filled('lab_id')) {
            $query->where('lab_id', $request->lab_id);
        }

        if ($request->filled('nomor_pc')) {
            $pc = (int) $request->nomor_pc;
            $query->where(function ($innerQuery) use ($pc) {
                $innerQuery->where('nomor_pc', $pc)
                    ->orWhereHas('ticket', function ($ticketQuery) use ($pc) {
                        $ticketQuery->where('nomor_pc', $pc);
                    });
            });
        }

        return $query;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $histories = $this->buildHistoryQuery($request)->get();
        $labs = Lab::orderBy('nama_lab')->get();

        return view('histories.index', compact('histories', 'labs', 'user'));
    }

    public function print(Request $request)
    {
        $histories = $this->buildHistoryQuery($request)->get();

        return view('histories.print', compact('histories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $labs = Lab::all();
        $tickets = Ticket::all();
        return view('histories.create', compact('labs','tickets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'lab_id' => ['required','exists:labs,id'],
            'ticket_id' => ['nullable','exists:tickets,id'],
            'tindakan' => ['required','string'],
            'catatan' => ['nullable','string'],
            'tanggal_perawatan' => ['required','date'],
        ]);
        $data['nomor_pc'] = 0;
        $data['asisten_nim'] = $request->user()->nim;
        MaintenanceHistory::create($data);
        return redirect()->route('histories.index')->with('success','History logged');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('histories.index')->with('success', 'Silakan lihat detail riwayat dari daftar utama.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $history = MaintenanceHistory::findOrFail($id);
        $labs = Lab::all();
        $tickets = Ticket::all();
        return view('histories.edit', compact('history','labs','tickets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $history = MaintenanceHistory::findOrFail($id);
        $data = $request->validate([
            'lab_id' => ['required','exists:labs,id'],
            'ticket_id' => ['nullable','exists:tickets,id'],
            'tindakan' => ['required','string'],
            'catatan' => ['nullable','string'],
            'tanggal_perawatan' => ['required','date'],
        ]);
        $data['nomor_pc'] = 0;
        $history->update($data);
        return redirect()->route('histories.index')->with('success','History updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $history = MaintenanceHistory::findOrFail($id);
        $history->delete();
        return redirect()->route('histories.index')->with('success','History deleted');
    }
}
