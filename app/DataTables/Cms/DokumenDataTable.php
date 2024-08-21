<?php

namespace App\DataTables\CMS;

use App\Models\Cms\Dokumen;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DokumenDataTable extends DataTable
{
    private bool $onlyTrashed = false;

    public function __construct()
    {
        // cek apakah route yang sedang diakses adalah route history
        if (request()->routeIs('cms.document.histori')) {
            $this->onlyTrashed = true;
        }
    }

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
                return view('pages.admin.cms.dokumen.action', ['data' => $val]);
            })
            ->editColumn('updated_at', function (Dokumen $val) {
                return view('components.table-timestamp', [
                    'date' => formatDateFromDatabase($val->updated_at),
                    'user' => $val->updatedBy,
                ]);
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
        if ($this->onlyTrashed) {
            // return only soft deleted data
            return $model->newQuery()->onlyTrashed()
                ->with('createdBy')
                ->with('updatedBy')
                ->orderBy('deleted_at', 'asc');
        }

        return $model->newQuery()
            ->with('createdBy')
            ->with('updatedBy')
            ->orderBy('created_at', 'asc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('dokumen-table')
            ->columns($this->getColumns())
            ->minifiedAjax(script: "
                        data._token = '".csrf_token()."';
                        data._p = 'POST';
                    ")
            ->addTableClass('table align-middle table-row-dashed gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold text-uppercase gs-0')
            ->responsive(true)
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
            Column::computed('DT_RowIndex')
                ->title('No.')
                ->width(20),
            Column::computed('aksi')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->title('Aksi')
                ->addClass('text-center'),
            Column::make('nama')->title('Nama'),
            Column::make('keterangan'),
            Column::make($this->onlyTrashed ? 'deleted_at' : 'updated_at')->title($this->onlyTrashed ? 'Terakhir Dihapus' : 'Terakhir Diubah'),
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
