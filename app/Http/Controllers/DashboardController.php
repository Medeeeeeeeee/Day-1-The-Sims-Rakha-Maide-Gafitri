<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\MaintenanceHistory;
use App\Models\MaintenanceSchedule;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard with statistics.
     */
    public function index(Request $request)
    {
        // this method now serves the student/guest dashboard; user may be null
        $user = $request->user();

        // compute basic ticket counts (same for both student and admin view)
        $total = Ticket::count();
        $pending = Ticket::where('status', 'pending')->count();
        $proses = Ticket::where('status', 'proses')->count();
        $selesai = Ticket::where('status', 'selesai')->count();

        // compute percents for progress bars (relative to max)
        $max = max($total, $proses, $selesai, 1);
        $pct = function($value) use ($max) {
            return round($value / $max * 100);
        };
        $pctTotal = $pct($total);
        $pctProses = $pct($proses);
        $pctSelesai = $pct($selesai);

        // tickets by month for the current year (computed in PHP for SQLite compatibility)
        $monthlyData = array_fill(1, 12, 0);
        $yearTickets = Ticket::whereYear('created_at', now()->year)->get(['created_at']);
        foreach ($yearTickets as $t) {
            $month = (int) $t->created_at->format('n');
            $monthlyData[$month]++;
        }

        // prepare month labels for chart
        $labels = array_map(function($m) {
            return date('M', mktime(0,0,0,$m,1));
        }, array_keys($monthlyData));

        // recent tickets (last 10)
        $recentTickets = Ticket::with('lab')->orderBy('created_at', 'desc')->limit(10)->get();

        // ticket count for sidebar badge
        $notif = Ticket::where('status','proses')->count();

        return view('dashboard', compact(
            'user', 'total', 'pending', 'proses', 'selesai',
            'pctTotal','pctProses','pctSelesai',
            'notif',
            'monthlyData', 'labels', 'recentTickets'
        ));
    }

    /**
     * Admin dashboard page – only accessible to logged in admins.
     */
    public function supervisor(Request $request)
    {
        $user = $request->user();
        // enforce supervisor role, abort 403 otherwise
        if (! $user || $user->role !== 'supervisor') {
            abort(403);
        }

        // count statuses for the cards at top
        $total = Ticket::count();
        $proses = Ticket::where('status', 'proses')->count();
        $selesai = Ticket::where('status', 'selesai')->count();

        // fetch all tickets for the table
        $tickets = Ticket::with('lab')->orderBy('created_at','desc')->get();

        // also compute notification badge (tickets in proses)
        $notif = Ticket::where('status','proses')->count();

        $recentTickets = Ticket::with('lab')->orderBy('created_at', 'desc')->limit(10)->get();

        return view('admin', compact('user','total','proses','selesai','tickets','notif','recentTickets'));
    }

    public function asisten(Request $request)
    {
        $user = $request->user();

        if (! $user || $user->role !== 'asisten') {
            abort(403);
        }

        $total = Ticket::count();
        $proses = Ticket::where('status', 'proses')->count();
        $selesai = Ticket::where('status', 'selesai')->count();
        $notif = Ticket::where('status','proses')->count();
        $recentTickets = Ticket::with('lab')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('asisten', compact(
            'user', 'total', 'proses', 'selesai', 'notif', 'recentTickets'
        ));
    }
}
