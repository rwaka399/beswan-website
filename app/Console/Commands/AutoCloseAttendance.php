<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendance;
use Carbon\Carbon;

class AutoCloseAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:auto-close';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically close expired attendances';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now()->format('H:i:s');
        $today = Carbon::today();

        // Cari attendance yang masih terbuka tapi sudah melewati close_time
        $expiredAttendances = Attendance::where('status', 'open')
            ->where('attendance_date', '<=', $today)
            ->where('close_time', '<', $now)
            ->get();

        $closedCount = 0;

        foreach ($expiredAttendances as $attendance) {
            $attendance->update(['status' => 'closed']);
            $closedCount++;
            $this->info("Closed attendance for date: {$attendance->attendance_date->format('Y-m-d')}");
        }

        if ($closedCount > 0) {
            $this->info("Successfully closed {$closedCount} expired attendance(s).");
        } else {
            $this->info("No expired attendances found.");
        }

        return 0;
    }
}
