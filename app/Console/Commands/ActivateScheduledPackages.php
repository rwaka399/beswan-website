<?php

namespace App\Console\Commands;

use App\Models\UserLessonPackage;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ActivateScheduledPackages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'packages:activate-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activate scheduled lesson packages that should start today';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        
        // Cari semua paket yang terjadwal untuk diaktifkan (berdasarkan scheduled_start_date)
        $scheduledPackages = UserLessonPackage::where('status', 'scheduled')
            ->whereDate('scheduled_start_date', '<=', $now->toDateString())
            ->get();

        $activatedCount = 0;

        foreach ($scheduledPackages as $package) {
            // Update status menjadi active dan set start_date ke sekarang
            $package->update([
                'status' => 'active',
                'start_date' => $now
            ]);
            
            Log::info("Activated scheduled package for user {$package->user_id}, package {$package->lesson_package_id}, scheduled start: {$package->scheduled_start_date}, actual start: {$now}");
            $activatedCount++;
        }

        $this->info("Activated {$activatedCount} scheduled packages.");
        
        return Command::SUCCESS;
    }
}
