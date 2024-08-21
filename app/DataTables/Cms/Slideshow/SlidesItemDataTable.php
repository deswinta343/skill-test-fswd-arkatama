<?php

namespace App\DataTables\Cms\Slideshow;

use App\Models\Cms\SlideShowItem;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SlidesItemDataTable extends DataTable
{
    protected $slideshow;

    public function __construct($slideshow = null)
    {
        if ($slideshow) {
            $this->slideshow = $slideshow;
        } else {
            $this->slideshow = request()->route('slideshow');
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
            ->addColumn('aksi', 'slideshowitem.action')
            ->addIndexColumn()
            ->addColumn('aksi', function (SlideShowItem $val) {
                return view('pages.admin.cms.slideshow.slide-item.action', ['slideshow' => $this->slideshow, 'item' => $val]);
            })
            ->editColumn('caption', function (SlideShowItem $val) {
                return substr($val->caption, 0, 50).'...';
            })
            ->editColumn('updated_at', function (SlideShowItem $val) {
                return view('components.table-timestamp', [
                    'date' => formatDateFromDatabase($val->updated_at),
                ]);
            })
            ->editColumn('deleted_at', function (SlideShowItem $val) {
                return view('components.table-timestamp', [
                    'date' => formatDateFromDatabase($val->deleted_at),
                ]);
            })
            ->rawColumns(['aksi', 'updated_at', 'deleted_at', 'caption'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(SlideShowItem $model): QueryBuilder
    {
        if (request()->routeIs('cms.slideshow-item.histori')) {
            return $model->newQuery()->onlyTrashed()->where('slideshow_id', $this->slideshow->id)->orderBy('created_at', 'desc');
        }

        return $model->newQuery()->where('slideshow_id', $this->slideshow->id)->orderBy('created_at', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('slideshowitem-table')
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
        $columns = [
            Column::computed('DT_RowIndex')
                ->title('No.'),
            Column::computed('aksi')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center'),
            Column::make('title')->title('Judul'),
            Column::make('caption'),
            Column::make('order'),
        ];

        if (request()->routeIs('cms.slideshow-item.histori')) {
            $columns[] = Column::make('deleted_at')
                ->title('Terakhir dihapus');
        } else {
            $columns[] = Column::make('updated_at')
                ->title('Terakhir diubah');
        }

        return $columns;
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Slideshowitem_'.date('YmdHis');
    }
}
