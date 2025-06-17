<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserLessonPackage;
use Carbon\Carbon;

class UpdateExpiredPackages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'packages:update-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status of expired lesson packages';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expired packages...');

        $expiredPackages = UserLessonPackage::where('status', 'active')
            ->where('end_date', '<', Carbon::now())
            ->get();

        $count = $expiredPackages->count();

        if ($count > 0) {
            $expiredPackages->each(function ($package) {
                $package->update(['status' => 'expired']);
                $this->line("Package ID {$package->user_lesson_package_id} for user ID {$package->user_id} marked as expired");
            });

            $this->info("Updated {$count} expired packages.");
        } else {
            $this->info('No expired packages found.');
        }

        return 0;
    }
}
