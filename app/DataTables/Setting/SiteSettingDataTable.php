<?php

namespace App\DataTables\Setting;

use App\Models\Setting\SiteSetting;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SiteSettingDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('aksi', function (SiteSetting $row) {
                return view('pages.admin.setting.site-settings.action', ['siteSetting' => $row]);
            })
            ->editColumn('type', function (SiteSetting $row) {
                if ($row->type == 'site-identity') {
                    return <<<'HTML'
                    <span class="badge bg-success">Site Identity</span>
                    HTML;
                } elseif ($row->type == 'hero') {
                    return <<<'HTML'
                    <span class="badge bg-primary">Hero</span>
                    HTML;
                } elseif ($row->type == 'profile') {
                    return <<<'HTML'
                    <span class="badge bg-info">Profile</span>
                    HTML;
                } else {
                    return <<<'HTML'
                    <span class="badge bg-dark">Unknown</span>
                    HTML;
                }
            })
            ->editColumn('deleted_at', function (SiteSetting $row) {
                return view('components.table-timestamp', [
                    'date' => formatDateFromDatabase($row->deleted_at),
                ]);
            })
            ->setRowId('id')
            ->rawColumns(['aksi', 'type']);
    }

    public function query(SiteSetting $model): QueryBuilder
    {
        if (request()->routeIs('setting.site-settings.histori')) {
            return $model->newQuery()->onlyTrashed()->latest('deleted_at');
        }

        return $model->newQuery()->latest('updated_at');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('sitesetting-table')
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

        $cols = [
            Column::computed('DT_RowIndex')
                ->title('No.')
                ->width(30),
            Column::computed('aksi')
                ->exportable(false)
                ->title('Aksi')
                ->printable(false)
                ->width(120)
                ->addClass('text-center'),
            Column::make('type')
                ->title('Tipe'),
            Column::make('name')
                ->title('Nama'),
            Column::make('value')
                ->title('Nilai'),
            Column::make('description')
                ->title('Deskripsi'),
        ];

        if (request()->routeIs('setting.site-settings.histori')) {
            $cols[] = Column::make('deleted_at')->title('Terakhir Dihapus')
                ->width(200)
                ->searchable(false)
                ->orderable(false)
                ->exportable(false)
                ->printable(false);
        }

        return $cols;
    }

    protected function filename(): string
    {
        return 'SiteSetting_'.date('YmdHis');
    }
}
