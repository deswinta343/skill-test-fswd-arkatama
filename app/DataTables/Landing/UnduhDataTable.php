<?php

namespace App\DataTables\Landing;

use App\Models\Cms\Dokumen;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UnduhDataTable extends DataTable
{
    /**
     * @var bool
     */
    public function __construct() {}

    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('aksi', 'file-manager.action')
            ->addIndexColumn()
            ->addColumn('aksi', function (Dokumen $val) {
                return view('pages.landing.unduh.action', ['data' => $val]);
            })
            ->editColumn('updated_at', function (Dokumen $val) {
                return view('components.table-timestamp', [
                    'date' => formatDateFromDatabase($val->updated_at),
                    'user' => $val->updatedBy,
                ]);
            })
            ->editColumn('nama', function (Dokumen $val) {
                return view('pages.landing.unduh.gambarDokumen', ['data' => $val]);
            })
            ->editColumn('deleted_at', function (Dokumen $val) {
                return view('components.table-timestamp', [
                    'date' => formatDateFromDatabase($val->deleted_at),
                ]);
            })
            ->rawColumns(['aksi', 'updated_at', 'deleted_at'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Dokumen $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('unduh-table')
            ->responsive(true)
            ->columns($this->getColumns())
            ->minifiedAjax(script: "
                        data._token = '".csrf_token()."';
                        data._p = 'POST';
                    ")
            ->addTableClass('table align-middle table-row-dashed gy-5 dataTable no-footer text-black fw-bolder')
            ->setTableHeadClass('text-start p-3 text-muted fw-bold text-uppercase gs-0')
            ->select(false)
            ->drawCallbackWithLivewire(file_get_contents(public_path('assets/js/dataTables/drawCallback.js')))
            ->buttons([]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('nama')->title('Nama Dokumen'),
            Column::make('updated_at')->title('Terakhir Diubah'),
            Column::computed('aksi')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->title('')
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Dokumen_'.date('YmdHis');
    }
}
