<?php

namespace App\Console\Commands;

use App\Models\Setting\BackupSchedule;
use Illuminate\Console\Command;

class BackupRun extends Command
{
    protected $signature = 'backup:run {backup_name}';

    protected $description = 'Backup database';

    protected BackupSchedule $backupSchedule;

    public function handle()
    {
        $backupName = $this->argument('backup_name');
        $backupSchedule = BackupSchedule::where('name', "{$backupName}")->first();
        if (! $backupSchedule) {
            $this->error("Backup schedule not found {$backupName}");

            return;
        }
        $this->backup($backupSchedule);
    }

    public function backup(BackupSchedule $backupSchedule)
    {

        $dbHost = env('DB_HOST');
        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');

        $backupDir = storage_path('backup\databases');

        if (! file_exists($backupDir)) {
            mkdir($backupDir, 0777, true);
        }
        $fileName = 'dump-tables-'.now()->format('Y-m-d-H-i-s').'.sql';
        $filePath = "{$backupDir}/{$fileName}";

        $selectedTables = $backupSchedule->backupTables()->pluck('table_name')->toArray();
        $selectedTables = implode(' ', $selectedTables);
        $command = '(mysqldump -u'.$dbUser.' -h '.$dbHost.' '.($dbPass ? "-p {$dbPass}" : '').' '.$dbName.' '.$selectedTables.' > "'.$filePath.'") 2>&1';
        try {
            exec($command, $output, $code);
            if ($code == 0) {

                if (! file_exists($filePath)) {
                    $backupSchedule->backupHistories()->create([
                        'status' => 'fail',
                    ]);
                    $this->error('Backup file not created');

                    return;
                } else {
                    $backupSchedule->backupHistories()->create([
                        'status' => 'success',
                        'file_name' => $fileName,
                    ]);
                    $this->info(1);

                    return;
                }
            }
        } catch (\Exception $e) {
            $backupSchedule->backupHistories()->create([
                'status' => 'fail',
            ]);
            $this->error('Backup failed');

            return;
        }
    }
}
