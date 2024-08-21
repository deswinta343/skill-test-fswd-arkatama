<?php

namespace App\DataTables\Cms;

use App\Models\Cms\FileManagement;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class FileManagerDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $dataTable = (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('action', function (FileManagement $val) {
                return view('pages.admin.cms.file-manager.action', ['file' => $val]);
            })
            ->editColumn('status', function (FileManagement $val) {
                return '<span class="badge bg-'.($val->status == '1' ? 'success' : 'danger').'">'.($val->status == '1' ? 'Active' : 'Inactive').'</span>';
            })
            ->editColumn('file', function (FileManagement $val) {
                return <<<HTML
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px me-5">
                            <span class="symbol-label bg-light">
                                <img src="assets/media/icons/{$val->file_icon}" class="h-50 align-top" alt="">
                            </span>
                        </div>
                        <div class="d-flex flex-column">
                            <span>{$val->file_type}</span>
                        </div>
                    </div>
                HTML;
            })
            ->editColumn('updated_at', function (FileManagement $val) {
                return view('components.table-timestamp', [
                    'date' => formatDateFromDatabase($val->updated_at),
                ]);
            })
            ->rawColumns(['action', 'status', 'file', 'updated_at'])
            ->setRowId('id');

        return $dataTable;
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(FileManagement $model): QueryBuilder
    {
        return $model->newQuery()->where('user_id', auth()->id())->latest('updated_at');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('filemanagement-table')
            ->columns($this->getColumns())
            ->minifiedAjax(script: "
                        data._token = '".csrf_token()."';
                        data._p = 'POST';
                    ")
            ->addTableClass('table align-middle table-row-dashed gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold text-uppercase gs-0')
            ->select(false)
            ->responsive(true)
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
                ->width(30),
            Column::computed('action')
                ->title('Aksi')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
            Column::computed('file')
                ->title('File'),
            Column::make('keterangan'),
            Column::make('status'),
            Column::make('updated_at')->title('Terakhir Diubah'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'FileManger_'.date('YmdHis');
    }
}
