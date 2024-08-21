<?php

namespace App\DataTables\Cms;

use App\Models\Cms\Timeline;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TimelineDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {

        return (new EloquentDataTable($query))
            ->addColumn('aksi', 'timeline.action')
            ->addIndexColumn()
            ->addColumn('aksi', function (Timeline $row) {
                return view('pages.admin.cms.timeline.action', ['timeline' => $row]);
            })
            ->editColumn('tanggal_mulai_pendaftaran', function (Timeline $timeline) {
                return view('components.table-timestamp', [
                    'date' => formatDateFromDatabase($timeline->tanggal_mulai_pendaftaran),
                ]);
            })
            ->editColumn('tanggal_selesai_pendaftaran', function (Timeline $timeline) {
                return view('components.table-timestamp', [
                    'date' => formatDateFromDatabase($timeline->tanggal_selesai_pendaftaran),
                ]);
            })
            ->editColumn('tahun_ajaran.is_current', function (Timeline $val) {
                return '<span class="badge bg-'.($val->tahun_ajaran->is_current == '1' ? 'success' : 'danger').'">'.($val->tahun_ajaran->is_current == '1' ? 'Ya' : 'Tidak').'</span>';
            })
            ->editColumn('tanggal_mulai_administrasi', function (Timeline $timeline) {
                return view('components.table-timestamp', [
                    'date' => formatDateFromDatabase($timeline->tanggal_mulai_administrasi),
                ]);
            })
            ->editColumn('tanggal_selesai_administrasi', function (Timeline $timeline) {
                return view('components.table-timestamp', [
                    'date' => formatDateFromDatabase($timeline->tanggal_selesai_administrasi),
                ]);
            })
            ->editColumn('tanggal_mulai_assesmen', function (Timeline $timeline) {
                return view('components.table-timestamp', [
                    'date' => formatDateFromDatabase($timeline->tanggal_mulai_assesmen),
                ]);
            })
            ->editColumn('tanggal_seleksi_evaluasi_diri', function (Timeline $timeline) {
                return view('components.table-timestamp', [
                    'date' => formatDateFromDatabase($timeline->tanggal_seleksi_evaluasi_diri),
                ]);
            })
            ->editColumn('updated_at', function (Timeline $timeline) {
                return view('components.table-timestamp', [
                    'date' => formatDateFromDatabase($timeline->updated_at),
                    'user' => $timeline->updatedBy,
                ]);
            })
            ->editColumn('deleted_at', function (Timeline $timeline) {
                return view('components.table-timestamp', [
                    'date' => formatDateFromDatabase($timeline->deleted_at),
                    'user' => $timeline->deletedBy,
                ]);
            })
            ->rawColumns(['aksi', 'tahun_ajaran.is_current', 'tanggal_mulai_pendaftaran', 'tanggal_selesai_pendaftaran', 'tanggal_mulai_administrasi', 'tanggal_selesai_administrasi', 'tanggal_mulai_assesmen', 'tanggal_seleksi_evaluasi_diri', 'updated_at', 'deleted_at'])
            ->setRowId('id');
    }

    public function query(Timeline $model): QueryBuilder
    {
        if (request()->routeIs('cms.timeline.histori')) {
            return $model->newQuery()->with('tahun_ajaran')->onlyTrashed()->orderBy('created_at', 'desc');
        }

        return $model->newQuery()->with('tahun_ajaran')->orderBy('created_at', 'desc');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('timeline-table')
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
            Column::make('tahun_ajaran.tahun_ajaran')
                ->title('Tahun Ajaran')
                ->data('tahun_ajaran.tahun_ajaran')
                ->searchable(true),
            Column::make('tanggal_mulai_pendaftaran')
                ->title('Mulai Pendaftaran'),
            Column::make('tanggal_selesai_pendaftaran')
                ->title('Selesai Pendaftaran'),
            Column::make('tanggal_mulai_administrasi')
                ->title('Mulai Administrasi'),
            Column::make('tanggal_selesai_administrasi')
                ->title('Selesai Administrasi'),
            Column::make('tanggal_mulai_assesmen')
                ->title('Mulai Assesmen'),
            Column::make('tanggal_seleksi_evaluasi_diri')
                ->title('Seleksi Evaluasi Diri'),
            Column::make('tahun_ajaran.is_current')->title('Semester Saat Ini'),

        ];

        if (request()->routeIs('cms.timeline.histori')) {
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
        return 'Timeline_'.date('YmdHis');
    }
}
