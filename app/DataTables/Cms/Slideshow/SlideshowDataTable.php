<?php

namespace App\DataTables\Cms\Slideshow;

use App\Models\Cms\SlideShow;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SlideshowDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {

        return (new EloquentDataTable($query))
            ->addColumn('aksi', 'slideshow.action')
            ->addIndexColumn()
            ->addColumn('aksi', function (SlideShow $row) {
                return view('pages.admin.cms.slideshow.action', ['slideshow' => $row]);
            })
            ->editColumn('is_active', function (SlideShow $val) {
                return '<span class="badge bg-'.($val->is_active == '1' ? 'success' : 'danger').'">'.($val->is_active == '1' ? 'Aktif' : 'Tidak Aktif').'</span>';
            })
            ->editColumn('updated_at', function (SlideShow $val) {
                return view('components.table-timestamp', [
                    'date' => formatDateFromDatabase($val->updated_at),
                ]);
            })
            ->editColumn('deleted_at', function (SlideShow $val) {
                return view('components.table-timestamp', [
                    'date' => formatDateFromDatabase($val->deleted_at),
                ]);
            })
            ->rawColumns(['aksi', 'is_active', 'updated_at', 'deleted_at'])
            ->setRowId('id');
    }

    public function query(SlideShow $model): QueryBuilder
    {
        if (request()->routeIs('cms.slideshow.histori')) {
            return $model->newQuery()->onlyTrashed()->orderBy('is_active', 'desc');
        }

        return $model->newQuery()->orderBy('is_active', 'desc');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('slideshow-table')
            ->columns($this->getColumns())
            ->minifiedAjax(script: "
                        data._token = '".csrf_token()."';
                        data._p = 'POST';
                    ")
            ->addTableClass('table align-middle table-row-dashed  gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold  text-uppercase gs-0')
            ->drawCallbackWithLivewire(file_get_contents(public_path('assets/js/dataTables/drawCallback.js')))
            ->responsive(true)
            ->select(false)
            ->buttons([]);
    }

    public function getColumns(): array
    {
        $columns = [
            Column::computed('DT_RowIndex')
                ->title('No.')
                ->width(20),
            Column::computed('aksi')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
            Column::make('name')->title('Nama'),
            Column::make('description')->title('Deskripsi'),
            Column::make('is_active')->title('Status'),
        ];

        if (request()->routeIs('cms.slideshow.histori')) {
            $columns[] = Column::make('deleted_at')
                ->title('Terakhir dihapus');
        } else {
            $columns[] = Column::make('updated_at')
                ->title('Terakhir diubah');
        }

        return $columns;
    }

    protected function filename(): string
    {
        return 'Slideshow_'.date('YmdHis');
    }
}
