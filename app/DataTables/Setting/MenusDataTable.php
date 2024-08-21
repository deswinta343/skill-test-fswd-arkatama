<?php

namespace App\DataTables\Setting;

use App\Models\Setting\Menus;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MenusDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $dataTable = (new EloquentDataTable($query))
            ->addColumn('aksi', 'menus.action')
            ->addIndexColumn()
            ->addColumn('aksi', function (Menus $val) {
                return view('pages.admin.setting.menus.action', ['menus' => $val]);
            })
            ->editColumn('is_active', function (Menus $val) {
                return '<span class="badge bg-'.($val->is_active == '1' ? 'success' : 'danger').'">'.($val->is_active == '1' ? 'Active' : 'Inactive').'</span>';
            })
            ->editColumn('icon', function (Menus $val) {
                return '<span class="menu-icon"><i class="fs-5 '.$val->icon.'"></i></span>';
            })
            ->editColumn('parent_id', function (Menus $val) {
                return $val->parent_id == null ? '' : DB::table('menus')->where('id', $val->parent_id)->first()->name;
            })
            ->rawColumns(['aksi', 'is_active', 'icon'])
            ->setRowId('id');

        return $dataTable;
    }

    public function query(): QueryBuilder
    {
        return Menus::orderBy('updated_at', 'desc')->orderBy('name', 'asc');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('menus-table')
            ->columns($this->getColumns())
            ->minifiedAjax(script: "
                        data._token = '".csrf_token()."';
                        data._p = 'POST';
                    ")

            ->addTableClass('table align-middle table-row-dashed gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold text-uppercase gs-0')
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
            Column::make('url'),
            // Column::make("module"),
            Column::make('order'),
            // Column::make("slug"),
            Column::make('is_active')
                ->title('Status'),
            Column::make('parent_id')
                ->title('Parent'),
            Column::make('icon'),
            Column::make('location')
                ->title('Lokasi'),
        ];
    }

    protected function filename(): string
    {
        return 'Menus_'.date('YmdHis');
    }
}
