<?php

namespace App\DataTables\Master;

use App\Models\Master\KabupatenKota;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class KabupatenKotaDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('aksi', 'kabupatenkota.action')
            ->addIndexColumn()
            ->addColumn('aksi', function (KabupatenKota $row) {
                return view('pages.admin.master.kabupaten.action', ['kabupatenkota' => $row]);
            })
            ->editColumn('provinsi_id', function (KabupatenKota $kabupatenkota) {
                return $kabupatenkota->provinsi->nama;
            })
            ->editColumn('created_at', function (KabupatenKota $kabupatenkota) {
                return view('components.table-timestamp', [
                    'date' => formatDateFromDatabase($kabupatenkota->created_at),
                ]);
            })
            ->editColumn('updated_at', function (KabupatenKota $kabupatenkota) {
                if ($kabupatenkota->updated_at == null) {
                    return view('components.table-timestamp', [
                        'date' => formatDateFromDatabase($kabupatenkota->created_at),
                    ]);
                } else {
                    return view('components.table-timestamp', [
                        'date' => formatDateFromDatabase($kabupatenkota->updated_at),
                    ]);
                }
            })
            ->rawColumns(['aksi'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(KabupatenKota $model): QueryBuilder
    {
        if (request()->routeIs('master.kabupaten-kota.histori')) {
            return $model->orderBy('created_at', 'desc')->onlyTrashed()
                ->newQuery()->with(['provinsi' => function ($query) {
                    $query->withTrashed();
                }]);
        } else {
            return $model->orderBy('created_at', 'desc')->newQuery();
        }
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('kabupatenkota-table')
            ->columns($this->getColumns())
            ->minifiedAjax(script: "
                        data._token = '".csrf_token()."';
                        data._p = 'POST';
                    ")
            ->addTableClass('table align-middle table-row-dashed  gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold  text-uppercase gs-0')
            ->drawCallbackWithLivewire(file_get_contents(public_path('/assets/js/dataTables/drawCallback.js')))
            ->select(false)
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
                ->addClass('text-center')->title('Aksi'),
            // Column::make('kode')->title('Kode'),
            Column::make('provinsi_id')->title('Provinsi'),
            Column::make('nama')->title('Kabupaten/Kota'),
            // Column::make('created_at'),
            Column::make('updated_at')->title('Terakhir Diubah'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'KabupatenKota_'.date('YmdHis');
    }
}
