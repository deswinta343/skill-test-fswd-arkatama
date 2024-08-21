<?php

namespace App\DataTables\Setting;

use App\Models\Setting\BackupHistory;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BackupHistoryDataTable extends DataTable
{
    protected $backupScheduleId;

    public function __construct($backupSchedule)
    {
        $this->backupScheduleId = $backupSchedule->id;
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (BackupHistory $backupHistory) {
                if ($backupHistory?->status == 'success' && $backupHistory?->file_name != null) {
                    return '<a href="'.route('setting.backup.download', ['backup_name' => $backupHistory->file_name]).'"
                                                    class="btn btn-light btn-active-light-primary p-3 btn-center btn-sm">
                                                    <i class="ti ti-download fs-3">
                                                    </i>
                                                </a>';
                } else {

                }
            })->editColumn('created_at', function (BackupHistory $backupHistory) {
                return $backupHistory->created_at->format('d-m-Y H:i:s');
            })->editColumn('status', function (BackupHistory $backupHistory) {
                if ($backupHistory->status == 'success') {
                    return '<div class="badge bg-success">Berhasil</div>';
                } else {
                    return '<div class="badge bg-danger">Gagal</div>';
                }
            })
            ->rawColumns(['action', 'status'])
            ->addIndexColumn()
            ->setRowId('id');
    }

    public function query(BackupHistory $model): QueryBuilder
    {
        return $model->newQuery()
            ->where('backup_schedule_id', $this->backupScheduleId)
            ->orderBy('created_at', 'desc');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('backuphistory-table')
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
                ->width(20),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-center')
                ->title('Action')
                ->orderable(false)
                ->searchable(false),
            Column::make('created_at')->title('Waktu Backup'),
            Column::make('status')->title('Status'),
            Column::make('file_name')->title('Nama File'),
        ];
    }

    protected function filename(): string
    {
        return 'BackupHistory_'.date('YmdHis');
    }
}
