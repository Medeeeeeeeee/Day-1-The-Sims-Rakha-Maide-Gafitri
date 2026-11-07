<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaintenanceSchedule;
use App\Models\Lab;
use App\Models\User;
use Illuminate\Support\Carbon;

class MaintenanceScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $user = $request->user();

            if (! $user || ! in_array($user->role, ['supervisor', 'asisten'], true)) {
                abort(403);
            }

            return $next($request);
        });

        $this->middleware(function ($request, $next) {
            if (! $request->user() || $request->user()->role !== 'supervisor') {
                abort(403);
            }

            return $next($request);
        })->only(['create', 'store', 'edit', 'update', 'destroy']);

        $this->middleware(function ($request, $next) {
            if (! $request->user() || $request->user()->role !== 'asisten') {
                abort(403);
            }

            return $next($request);
        })->only(['complete']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = request()->user();
        $labs = Lab::orderBy('nama_lab')->get();
        $monthInput = $request->input('month', now()->format('Y-m'));

        try {
            $selectedMonth = Carbon::createFromFormat('Y-m', $monthInput)->startOfMonth();
        } catch (\Throwable $exception) {
            $selectedMonth = now()->startOfMonth();
        }

        $selectedLabId = $request->input('lab_id');
        if (! $selectedLabId && $labs->isNotEmpty()) {
            $selectedLabId = (string) $labs->first()->id;
        }

        $query = MaintenanceSchedule::with(['lab', 'asisten']);

        if ($user->role === 'asisten') {
            $query->where('asisten_nim', $user->nim);
        }

        if ($selectedLabId) {
            $query->where('lab_id', $selectedLabId);
        }

        $query->whereBetween('tanggal_jadwal', [
            $selectedMonth->copy()->startOfMonth(),
            $selectedMonth->copy()->endOfMonth(),
        ]);

        $schedules = $query->orderBy('tanggal_jadwal')->get();
        $total = $schedules->count();
        $proses = $schedules->where('status_jadwal', 'menunggu')->count();
        $selesai = $schedules->where('status_jadwal', 'selesai')->count();

        $selectedLab = $labs->firstWhere('id', (int) $selectedLabId);
        $scheduleMap = $schedules
            ->groupBy(fn ($schedule) => Carbon::parse($schedule->tanggal_jadwal)->format('Y-m-d'))
            ->map(fn ($items) => $items->values());

        $calendarStart = $selectedMonth->copy()->startOfWeek(Carbon::MONDAY);
        $calendarEnd = $selectedMonth->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);
        $calendarWeeks = [];
        $cursor = $calendarStart->copy();

        while ($cursor->lte($calendarEnd)) {
            $week = [];

            for ($dayIndex = 0; $dayIndex < 7; $dayIndex++) {
                $dateKey = $cursor->format('Y-m-d');
                $daySchedules = $scheduleMap->get($dateKey, collect());
                $menungguCount = $daySchedules->where('status_jadwal', 'menunggu')->count();
                $selesaiCount = $daySchedules->where('status_jadwal', 'selesai')->count();

                $week[] = [
                    'date' => $cursor->copy(),
                    'date_key' => $dateKey,
                    'in_month' => $cursor->month === $selectedMonth->month,
                    'is_today' => $cursor->isSameDay(now()),
                    'items' => $daySchedules,
                    'menunggu_count' => $menungguCount,
                    'selesai_count' => $selesaiCount,
                ];

                $cursor->addDay();
            }

            $calendarWeeks[] = $week;
        }

        return view('schedules.index', compact(
            'user',
            'labs',
            'schedules',
            'total',
            'proses',
            'selesai',
            'selectedLabId',
            'selectedLab',
            'selectedMonth',
            'calendarWeeks'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $labs = Lab::all();
        $assistants = User::where('role', 'asisten')->orderBy('nim')->get();

        return view('schedules.create', compact('labs', 'assistants'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'lab_id' => ['required','exists:labs,id'],
            'asisten_nim' => ['required','exists:users,nim'],
            'tanggal_jadwal' => ['required','date'],
            'keterangan' => ['nullable','string'],
        ]);
        $data['nomor_pc'] = 0;

        $assistant = User::where('nim', $data['asisten_nim'])->where('role', 'asisten')->first();

        if (! $assistant) {
            return back()->withErrors([
                'asisten_nim' => 'Asisten yang dipilih tidak valid.',
            ])->withInput();
        }

        MaintenanceSchedule::create($data);

        return redirect()->route('schedules.index')->with('success','Jadwal maintenance berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $schedule = MaintenanceSchedule::with(['lab','asisten'])->findOrFail($id);

        return redirect()->route('schedules.index')->with('success', 'Detail jadwal ditampilkan melalui daftar jadwal.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $schedule = MaintenanceSchedule::findOrFail($id);
        $labs = Lab::all();
        $assistants = User::where('role', 'asisten')->orderBy('nim')->get();

        return view('schedules.edit', compact('schedule', 'labs', 'assistants'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $schedule = MaintenanceSchedule::findOrFail($id);
        $data = $request->validate([
            'lab_id' => ['required','exists:labs,id'],
            'asisten_nim' => ['required','exists:users,nim'],
            'tanggal_jadwal' => ['required','date'],
            'keterangan' => ['nullable','string'],
            'status_jadwal' => ['nullable','in:menunggu,selesai'],
        ]);

        $assistant = User::where('nim', $data['asisten_nim'])->where('role', 'asisten')->first();

        if (! $assistant) {
            return back()->withErrors([
                'asisten_nim' => 'Asisten yang dipilih tidak valid.',
            ])->withInput();
        }

        $data['nomor_pc'] = 0;
        $schedule->update($data);
        return redirect()->route('schedules.index')->with('success','Schedule updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $schedule = MaintenanceSchedule::findOrFail($id);
        $schedule->delete();
        return redirect()->route('schedules.index')->with('success','Schedule deleted');
    }

    public function complete(Request $request, MaintenanceSchedule $schedule)
    {
        $user = $request->user();

        if ((int) $schedule->asisten_nim !== (int) $user->nim) {
            abort(403);
        }

        if ($schedule->status_jadwal !== 'selesai') {
            $schedule->update([
                'status_jadwal' => 'selesai',
            ]);
        }

        return back()->with('success', 'Jadwal maintenance berhasil ditandai selesai.');
    }
}
