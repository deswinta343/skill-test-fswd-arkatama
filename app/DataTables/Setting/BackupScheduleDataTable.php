<?php

namespace App\DataTables\Setting;

use App\Models\Setting\BackupSchedule;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BackupScheduleDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('aksi', function (BackupSchedule $backupSchedule) {
                return view('pages.admin.setting.backup.action')
                    ->with(['backupSchedule' => $backupSchedule]);
            })->addIndexColumn()
            ->setRowId('id');
    }

    public function query(BackupSchedule $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('backupschedule-table')
            ->columns($this->getColumns())
            ->minifiedAjax(script: "
                        data._token = '".csrf_token()."';
                        data._p = 'POST';
                    ")
            ->addTableClass('table align-middle table-row-dashed  gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold text-uppercase gs-0')
            ->drawCallbackWithLivewire(file_get_contents(public_path('assets/js/dataTables/drawCallback.js')))
            ->select(false)
            ->buttons([]);
    }

    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')
                ->title('No.')
                ->width(30),
            Column::computed('aksi')
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-center')
                ->title('Aksi')
                ->orderable(false)
                ->searchable(false),
            Column::make('name')->searchable()
                ->title('Nama'),
            Column::make('frequency')->searchable()
                ->title('Frekuensi'),
            Column::make('time')
                ->title('Waktu'),
        ];
    }

    protected function filename(): string
    {
        return 'BackupSchedule_'.date('YmdHis');
    }
}
