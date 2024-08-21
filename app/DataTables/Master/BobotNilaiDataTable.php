<?php

namespace App\DataTables\Master;

use App\Models\Master\BobotNilai;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BobotNilaiDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('aksi', 'bobot-nilai.action')
            ->addIndexColumn()
            ->addColumn('aksi', function (BobotNilai $row) {
                return view('pages.admin.master.bobot-nilai.action', ['bobotnilai' => $row]);
            })
            // ->editColumn('created_at', function (BobotNilai $bobotnilai) {
            //     return $bobotnilai->created_at->format('d, M Y H:i');
            // })
            ->editColumn('updated_at', function (BobotNilai $bobotnilai) {
                return view('components.table-timestamp', [
                    'date' => formatDateFromDatabase($bobotnilai->updated_at),
                ]);
            })
            ->rawColumns(['aksi'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(BobotNilai $model): QueryBuilder
    {
        if (request()->routeIs('master.bobot-nilai.histori')) {
            return $model->orderBy('created_at', 'desc')->onlyTrashed()->newQuery();
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
            ->setTableId('bobot-nilai-table')
            ->columns($this->getColumns())
            ->minifiedAjax(script: "
                        data._token = '".csrf_token()."';
                        data._p = 'POST';
                    ")
            ->addTableClass('table align-middle table-row-dashed gy-5 dataTable no-footer text-gray-600 fw-semibold')
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
            Column::make('nilai_huruf')->title('Nilai Huruf'),
            Column::make('nilai_min')->title('Nilai Minimal'),
            Column::make('nilai_max')->title('Nilai Maksimal'),
            // Column::make('created_at'),
            Column::make('updated_at')->title('Terakhir Diubah'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'BobotNilai_'.date('YmdHis');
    }
}
