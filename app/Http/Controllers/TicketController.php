<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Lab;
use App\Models\MaintenanceHistory;

class TicketController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Ticket::with('lab')->orderBy('created_at', 'desc');

        $tickets = $query->get();
        $user = $request->user();

        if ($user && $user->role === 'asisten') {
            return redirect()->route('asisten.dashboard');
        }

        if ($user && $user->role === 'supervisor') {
            return redirect()->route('admin.dashboard');
        }

        return view('tickets.index', compact('tickets'));
    }

    protected function authorizeAction()
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['supervisor','asisten'])) {
            abort(403);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $labs = Lab::all();
        return view('tickets.create', compact('labs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'lab_id' => ['required', 'exists:labs,id'],
            'nomor_pc' => ['required', 'integer', 'min:1'],
            'nama_pelapor' => ['nullable', 'string', 'max:255'],
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            // ruangan is front-end only, ignore on save
            'ruangan' => ['nullable', 'string', 'max:50'],
        ]);

        $lab = Lab::findOrFail($data['lab_id']);
        if ($data['nomor_pc'] > $lab->pc_count) {
            return back()
                ->withErrors(['nomor_pc' => "Nomor PC harus antara 1 dan {$lab->pc_count} untuk Lab {$lab->nama_lab}." ])
                ->withInput();
        }

        Ticket::create($data);

        return redirect()->route('dashboard')->with('success', 'Ticket submitted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ticket = Ticket::with('lab')->findOrFail($id);

        if (auth()->check() && in_array(auth()->user()->role, ['supervisor', 'asisten'])) {
            return view('tickets.handle', compact('ticket'));
        }

        return view('tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorizeAction();

        $ticket = Ticket::findOrFail($id);
        $labs = Lab::all();

        return view('tickets.handle', compact('ticket', 'labs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorizeAction();

        $ticket = Ticket::findOrFail($id);

        $data = $request->validate([
            'lab_id' => ['required', 'exists:labs,id'],
            'nomor_pc' => ['required', 'integer', 'min:1'],
            'nama_pelapor' => ['nullable', 'string', 'max:255'],
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'status' => ['nullable', 'in:pending,proses,selesai'],
            'tindakan' => ['nullable', 'string'],
        ]);

        $lab = Lab::findOrFail($data['lab_id']);
        if ($data['nomor_pc'] > $lab->pc_count) {
            return back()
                ->withErrors(['nomor_pc' => "Nomor PC harus antara 1 dan {$lab->pc_count} untuk Lab {$lab->nama_lab}." ])
                ->withInput();
        }

        if (isset($data['status']) && $data['status'] === 'selesai') {
            $data['tanggal_selesai'] = now();
        }

        $ticket->update($data);

        if ($request->filled('tindakan') && auth()->check()) {
            MaintenanceHistory::create([
                'lab_id' => $data['lab_id'],
                'nomor_pc' => $data['nomor_pc'],
                'ticket_id' => $ticket->id,
                'asisten_nim' => auth()->user()->nim,
                'tindakan' => $request->input('tindakan'),
                'catatan' => 'Respon penanganan ticket oleh ' . auth()->user()->displayName(),
                'tanggal_perawatan' => now(),
            ]);
        }

        return redirect()->route('tickets.index')->with('success', 'Ticket updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorizeAction();

        $ticket = Ticket::findOrFail($id);
        $ticket->delete();
        return redirect()->route('tickets.index')->with('success', 'Ticket deleted.');
    }
}
