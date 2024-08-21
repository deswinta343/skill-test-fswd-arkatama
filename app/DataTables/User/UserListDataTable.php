<?php

namespace App\DataTables\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UserListDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('aksi', 'userlist.action')
            ->addIndexColumn()
            ->addColumn('aksi', function (User $val) {
                return view('pages.admin.user.user-list.action', ['user' => $val]);
            })
            ->editColumn('email_verified_at', function (User $val) {
                return view('components.table-timestamp', [
                    'date' => formatDateFromDatabase($val->email_verified_at),
                ]);
            })
            ->rawColumns(['aksi'])
            ->setRowId('id');
    }

    public function query(User $query): QueryBuilder
    {
        $role_id = request()->get('role_id');

        return $query->newQuery()->when($role_id, function ($query, $role_id) {
            return $query->whereHas('roles', function ($query) use ($role_id) {
                return $query->where('id', $role_id);
            });
        })->latest('created_at')->latest('updated_at');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('userlist-table')
            ->columns($this->getColumns())
            ->minifiedAjax(script: "
                        data._token = '".csrf_token()."';
                        data._p = 'POST';
                        data.role_id = $('#role_field4').val();
                    ")
            ->addTableClass('table align-middle table-row-dashed  gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->orders([2, 'asc'])
            ->setTableHeadClass('text-start text-muted fw-bold text-uppercase gs-0')
            ->responsive(true)
            ->select(false)
            ->drawCallbackWithLivewire(file_get_contents(public_path('assets/js/dataTables/drawCallback.js')))
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
            Column::make('email'),
            Column::make('email_verified_at')
                ->title('Terverifikasi Pada'),
        ];
    }

    protected function filename(): string
    {
        return 'UserList_'.date('YmdHis');
    }
}
