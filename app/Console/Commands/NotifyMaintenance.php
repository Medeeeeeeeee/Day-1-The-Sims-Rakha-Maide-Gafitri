<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class NotifyMaintenance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:maintenance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking upcoming maintenance schedules...');

        $schedules = \App\Models\MaintenanceSchedule::where('status_jadwal','menunggu')
            ->whereDate('tanggal_jadwal','<=', now())
            ->with('asisten')
            ->get();

        foreach ($schedules as $schedule) {
            $message = "Schedule #{$schedule->id} for PC {$schedule->nomor_pc} at lab {$schedule->lab->nama_lab} is due.\n";
            $message .= "Assigned to: " . ($schedule->asisten->name ?? 'unknown') . "\n";
            // for now we just log it; real implementation could send email or notification
            info($message);
            $this->line($message);
        }

        $this->info('Done.');
    }
}
