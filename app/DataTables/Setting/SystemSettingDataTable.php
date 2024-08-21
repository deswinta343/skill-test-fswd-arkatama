<?php

namespace App\DataTables\Setting;

use App\Models\Setting\SystemSettingModel;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SystemSettingDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $dataTable = (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('aksi', function (SystemSettingModel $row) {
                return view('pages.admin.setting.system-setting.action', ['systemSetting' => $row]);
            })
            ->editColumn('is_active', function (SystemSettingModel $val) {
                return '<span class="badge bg-'.($val->is_active == '1' ? 'success' : 'danger').'">'.($val->is_active == '1' ? 'Active' : 'Inactive').'</span>';
            })
            ->editColumn('icon', function (SystemSettingModel $val) {
                return '<span class="menu-icon"><i class="'.$val->icon.'"></i> '.$val->icon.'</span>';
            })
            ->editColumn('deleted_at', function (SystemSettingModel $val) {
                return view('components.table-timestamp', [
                    'date' => formatDateFromDatabase($val->deleted_at),
                ]);
            })
            ->rawColumns(['aksi', 'is_active', 'icon'])
            ->setRowId('id');

        return $dataTable;
    }

    public function query(SystemSettingModel $model): QueryBuilder
    {
        if (request()->routeIs('setting.system-setting.histori')) {
            return $model->newQuery()->onlyTrashed()->latest('deleted_at');
        }

        return $model->newQuery()->latest('updated_at');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('system-setting-table')
            ->columns($this->getColumns())
            ->minifiedAjax(script: "
                        data._token = '".csrf_token()."';
                        data._p = 'POST';
                    ")
            ->addTableClass('table align-middle table-row-dashed gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold text-uppercase gs-0')
            ->select(false)
            ->drawCallbackWithLivewire(file_get_contents(public_path('/assets/js/dataTables/drawCallback.js')))
            ->buttons([]);
    }

    public function getColumns(): array
    {

        $cols = [
            Column::computed('DT_RowIndex')
                ->title('No.')
                ->width(30),
            Column::computed('aksi')
                ->title('Aksi')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
            Column::make('name')
                ->title('Nama'),
            Column::make('is_active')
                ->title('Status'),
            Column::make('type')
                ->title('Tipe'),
            Column::make('value')
                ->title('Nilai'),
            Column::make('description')
                ->title('Deskripsi'),
        ];

        if (request()->routeIs('setting.system-setting.histori')) {
            $cols[] = Column::make('deleted_at')
                ->title('Terakhir Dihapus')
                ->width(200)
                ->searchable(false)
                ->orderable(false)
                ->exportable(false)
                ->printable(false);
        }

        return $cols;
    }

    protected function filename(): string
    {
        return 'SystemSettingModel_'.date('YmdHis');
    }
}
