<?php

namespace App\DataTables\User;

use App\Models\User\Role;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class RoleDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('aksi', 'role.action')
            ->addIndexColumn()
            ->addColumn('aksi', function (Role $val) {
                return view('pages.admin.user.role.action', ['role' => $val]);
            })

            ->editColumn('updated_at', function (Role $val) {
                return view('components.table-timestamp', [
                    'date' => formatDateFromDatabase($val->updated_at),
                ]);
            })
            ->rawColumns(['aksi'])
            ->setRowId('id');
    }

    public function query(Role $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('roles-table')
            ->columns($this->getColumns())
            ->minifiedAjax(script: "
                        data._token = '".csrf_token()."';
                        data._p = 'POST';
                    ")
            ->addTableClass('table align-middle table-row-dashed  gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold text-uppercase gs-0')
            ->responsive(true)
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
                ->title('Aksi')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
            Column::make('name')
                ->title('Nama'),
            Column::make('guard_name')
                ->title('Nama Guard'),
            Column::make('updated_at')
                ->title('Terakhir Diubah'),
        ];
    }

    protected function filename(): string
    {
        return 'Role_'.date('YmdHis');
    }
}
