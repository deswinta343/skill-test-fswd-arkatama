<?php

namespace App\DataTables\Cms;

use App\Models\Cms\FAQs;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class FAQsDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {

        return (new EloquentDataTable($query))
            ->addColumn('aksi', 'faqs.action')
            ->addIndexColumn()
            ->addColumn('aksi', function (FAQs $row) {
                return view('pages.admin.cms.faqs.action', ['faq' => $row]);
            })
            ->editColumn('question', function (FAQs $faq) {
                return substr($faq->question, 0, 50).'...';
            })
            ->editColumn('answer', function (FAQs $faq) {
                return substr($faq->answer, 0, 50).'...';
            })
            ->editColumn('is_active', function (FAQs $faq) {
                return '<span class="badge bg-'.($faq->is_active == '1' ? 'success' : 'danger').'">'.($faq->is_active == '1' ? 'Aktif' : 'Tidak Aktif').'</span>';
            })
            ->editColumn('created_by', function (FAQs $faq) {
                return $faq->author->name;
            })
            ->editColumn('updated_at', function (FAQs $faq) {
                return view('components.table-timestamp', [
                    'date' => formatDateFromDatabase($faq->updated_at),
                    'user' => $faq->updatedBy,
                ]);
            })
            ->editColumn('deleted_at', function (FAQs $faq) {
                return view('components.table-timestamp', [
                    'date' => formatDateFromDatabase($faq->deleted_at),
                    'user' => $faq->deletedBy,
                ]);
            })
            ->rawColumns(['aksi', 'question', 'answer', 'is_active', 'created_by', 'updated_at', 'deleted_at'])
            ->setRowId('id');
    }

    public function query(FAQs $model): QueryBuilder
    {
        if (request()->routeIs('cms.faqs.histori')) {
            return $model->newQuery()->onlyTrashed()->orderBy('created_at', 'desc');
        }

        return $model->newQuery()->orderBy('created_at', 'desc');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('faqs-table')
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
            Column::make('question')->title('Pertanyaan'),
            Column::make('answer')->title('Jawaban'),
            Column::make('is_active')->title('Status'),
        ];

        if (request()->routeIs('cms.faqs.histori')) {
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
        return 'FAQs_'.date('YmdHis');
    }
}
