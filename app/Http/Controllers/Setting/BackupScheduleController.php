<?php

namespace App\Http\Controllers\Setting;

use App\DataTables\Setting\BackupHistoryDataTable;
use App\DataTables\Setting\BackupScheduleDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\Backup\StoreBackupDbRequest;
use App\Models\Setting\BackupSchedule;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use ResponseFormatter;
use Throwable;

class BackupScheduleController extends Controller
{
    protected $modules = ['setting', 'settings.backup'];

    public function index(BackupScheduleDataTable $dataTable)
    {
        return $dataTable->render('pages.admin.setting.backup.index');
    }

    public function create()
    {
        $parsedTables = $this->getTableRef();

        return view('pages.admin.setting.backup.create', compact('parsedTables'));
    }

    public function store(StoreBackupDbRequest $request)
    {
        try {
            $newBackupSchedule = null;
            DB::transaction(function () use ($request) {
                $newBackupSchedule = BackupSchedule::create($request->validated());
                $tables = collect($request->tables)->map(function ($table) {
                    return ['table_name' => $table];
                });
                $newBackupSchedule->backupTables()->createMany($tables->toArray());
            });

            return ResponseFormatter::created('Berhasil membuat jadwal backup', $newBackupSchedule);
        } catch (\Throwable $th) {
            return ResponseFormatter::error('Gagal membuat jadwal backup, server error', [
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function show(BackupSchedule $backupSchedule)
    {
        $dataTable = new BackupHistoryDataTable($backupSchedule);

        $tableRef = $this->getTableRef();

        $currentTables = $backupSchedule->backupTables->pluck('table_name')->toArray();

        return $dataTable->render('pages.admin.setting.backup.show', [
            'ref_tables' => $tableRef,
            'backupSchedule' => $backupSchedule,
            'curr_tables' => $currentTables,
        ]);
    }

    public function edit(BackupSchedule $backupSchedule)
    {
        $backupSchedule->load('backupTables');
        $tableRef = $this->getTableRef();
        $currentTables = $backupSchedule->backupTables->pluck('table_name')->toArray();

        return view('pages.admin.setting.backup.edit', [
            'curr_tables' => $currentTables,
            'backupSchedule' => $backupSchedule,
            'ref_tables' => $tableRef,
        ]);
    }

    public function update(StoreBackupDbRequest $request, BackupSchedule $backupSchedule)
    {

        try {
            DB::transaction(function () use ($request, $backupSchedule) {
                $backupSchedule->update($request->validated());
                $tables = collect($request->tables)->map(function ($table) {
                    return ['table_name' => $table];
                });
                $backupSchedule->backupTables()->delete();
                $backupSchedule->backupTables()->createMany($tables->toArray());
            });

            return ResponseFormatter::success('Berhasil mengupdate jadwal backup', $backupSchedule);
        } catch (\Throwable $th) {
            return ResponseFormatter::error('Gagal mengupdate jadwal backup, server error', [
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function destroy(BackupSchedule $backupSchedule)
    {
        try {
            $backupSchedule->deleteOrFail();

            return ResponseFormatter::success('Berhasil menghapus jadwal backup', null);
        } catch (Throwable $err) {
            return ResponseFormatter::success('Gagal menghapus jadwal backup, server error', [
                'message' => $err->getMessage(),
            ], 500);
        }
    }

    public function run(BackupSchedule $backupSchedule)
    {

        Artisan::call('backup:run', ['backup_name' => $backupSchedule->name]);
        if (Artisan::output() == 1) {
            $latestBackupFile = $backupSchedule->backupHistories()->latest()->first()->file_name;
            if (! $latestBackupFile) {
                return ResponseFormatter::error('Gagal menjalankan backup, file backup tidak ditemukan', null, 500);
            }

            return ResponseFormatter::success('Berhasil menjalankan backup', $backupSchedule);

        }
    }

    public function download(string $fileName)
    {
        $filePath = storage_path('backup/databases/'.$fileName);

        return response()->download($filePath);
    }

    private function getTableRef()
    {
        $tableNames = DB::table('information_schema.tables')
            ->select('table_name')
            ->where('table_schema', config('database.connections.mysql.database'))
            ->pluck('TABLE_NAME')->toArray();
        $parsedTables = [];
        foreach ($tableNames as $table) {
            $parsedTables[$table] = ucfirst($table);
        }

        return $parsedTables;
    }
}
