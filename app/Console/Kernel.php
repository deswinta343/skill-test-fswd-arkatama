<?php

namespace App\Console;

use App\Models\Setting\BackupSchedule;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $backupSchedules = BackupSchedule::all();
        foreach ($backupSchedules as $backupSchedule) {
            $scheduleJob = $schedule->command('backup:run '.$backupSchedule->name);
            $scheduleTime = date('H:i', strtotime($backupSchedule->time));

            if ($backupSchedule->frequency == 'daily') {
                $scheduleJob->daily()->at($scheduleTime);
            } elseif ($backupSchedule->frequency == 'weekly') {
                $scheduleJob->weekly()->at($scheduleTime);
            } elseif ($backupSchedule->frequency == 'monthly') {
                $scheduleJob->monthly()->at($scheduleTime);
            }
            $scheduleJob->name("Backup-Schedule {$backupSchedule->name}")->withoutOverlapping()->timezone('Asia/Jakarta');
            $scheduleJob->appendOutputTo(storage_path("logs/backup-{$backupSchedule->name}.log"));
        }
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
