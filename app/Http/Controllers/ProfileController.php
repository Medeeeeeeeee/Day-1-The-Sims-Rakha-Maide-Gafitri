<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\MaintenanceHistory;
use App\Models\MaintenanceSchedule;

class ProfileController extends Controller
{
    /**
     * Show the authenticated user's profile page.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $notif = Ticket::where('status', 'proses')->count();
        $labels = array_map(function ($month) {
            return strtoupper(date('M', mktime(0, 0, 0, $month, 1)));
        }, range(1, 12));

        if ($user->isAdmin()) {
            $total = Ticket::count();
            $proses = Ticket::where('status', 'proses')->count();
            $selesai = Ticket::where('status', 'selesai')->count();
            $recentTickets = Ticket::with('lab')->latest()->limit(8)->get();
            $monthlyData = array_fill(1, 12, 0);
            $yearTickets = Ticket::whereYear('created_at', now()->year)->get(['created_at']);

            foreach ($yearTickets as $ticket) {
                $month = (int) $ticket->created_at->format('n');
                $monthlyData[$month]++;
            }

            return view('profiles.admin', compact(
                'user', 'notif', 'total', 'proses', 'selesai', 'recentTickets', 'monthlyData', 'labels'
            ));
        }

        if ($user->isAsisten()) {
            $scheduleCount = MaintenanceSchedule::where('asisten_nim', $user->nim)->count();
            $historyCount = MaintenanceHistory::where('asisten_nim', $user->nim)->count();
            $handledTickets = MaintenanceHistory::where('asisten_nim', $user->nim)
                ->distinct('ticket_id')
                ->count('ticket_id');
            $recentHistories = MaintenanceHistory::with(['lab', 'ticket'])
                ->where('asisten_nim', $user->nim)
                ->latest('tanggal_perawatan')
                ->limit(8)
                ->get();
            $monthlyData = array_fill(1, 12, 0);
            $yearHistories = MaintenanceHistory::where('asisten_nim', $user->nim)
                ->whereYear('tanggal_perawatan', now()->year)
                ->get(['tanggal_perawatan']);

            foreach ($yearHistories as $history) {
                if (! $history->tanggal_perawatan) {
                    continue;
                }

                $month = (int) \Illuminate\Support\Carbon::parse($history->tanggal_perawatan)->format('n');
                $monthlyData[$month]++;
            }

            return view('profiles.asisten', compact(
                'user', 'notif', 'scheduleCount', 'historyCount', 'handledTickets', 'recentHistories', 'monthlyData', 'labels'
            ));
        }

        $monthlyData = array_fill(1, 12, 0);
        $yearTickets = Ticket::whereYear('created_at', now()->year)->get(['created_at']);

        foreach ($yearTickets as $ticket) {
            $month = (int) $ticket->created_at->format('n');
            $monthlyData[$month]++;
        }

        return view('profiles.user', compact('user', 'notif', 'monthlyData', 'labels'));
    }
}
