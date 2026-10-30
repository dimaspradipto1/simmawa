<?php

namespace App\DataTables;

use App\Models\LogoProdi;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class LogoProdiDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<LogoProdi> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('logo_preview', function (LogoProdi $row) {
                return '<div class="d-flex align-items-center justify-content-center p-1 bg-light rounded border" style="width: 50px; height: 50px;">
                    <img src="' . e($row->logo_url) . '" alt="Logo" class="img-fluid" style="max-height: 42px; object-fit: contain;">
                </div>';
            })
            ->addColumn('kategori_badge', function (LogoProdi $row) {
                return $row->kategori_badge;
            })
            ->addColumn('organisasi', function (LogoProdi $row) {
                $singkatan = $row->singkatan ? '<span class="badge bg-dark me-1">' . e($row->singkatan) . '</span>' : '';
                $nama = $row->nama_organisasi ? e($row->nama_organisasi) : '<span class="text-muted fst-italic">-</span>';
                return '<div>' . $singkatan . '<span class="fw-semibold text-dark">' . $nama . '</span></div>';
            })
            ->editColumn('program_studi', function (LogoProdi $row) {
                $prodi = $row->program_studi ?: 'Semua Prodi (Lintas Fakultas)';
                $html = '<div class="fw-bold text-dark">' . e($prodi) . '</div>';
                if (!empty($row->fakultas)) {
                    $html .= '<small class="text-muted"><i class="bi bi-buildings me-1"></i>' . e($row->fakultas) . '</small>';
                }
                return $html;
            })
            ->editColumn('is_active', function (LogoProdi $row) {
                if ($row->is_active) {
                    return '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Aktif</span>';
                }
                return '<span class="badge bg-secondary"><i class="bi bi-dash-circle me-1"></i> Non-Aktif</span>';
            })
            ->addColumn('action', function (LogoProdi $row) {
                $editUrl = route('logo-prodi.edit', $row->id);
                $deleteUrl = route('logo-prodi.destroy', $row->id);
                $csrf = csrf_field();
                $method = method_field('DELETE');

                $btnEdit = '<a href="' . $editUrl . '" class="btn-action btn-action-edit" title="Edit Logo">
                                <i class="bi bi-pencil-square"></i>
                            </a>';

                $btnDelete = '
                    <form action="' . $deleteUrl . '" method="POST" class="d-inline m-0 p-0 delete-form">
                        ' . $csrf . '
                        ' . $method . '
                        <button type="button" class="btn-action btn-action-delete btn-delete" data-id="' . $row->id . '" title="Hapus Logo">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>';

                return '<div class="action-btn-group" role="group">' . $btnEdit . $btnDelete . '</div>';
            })
            ->rawColumns(['logo_preview', 'kategori_badge', 'organisasi', 'program_studi', 'is_active', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<LogoProdi>
     */
    public function query(LogoProdi $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('kategori', 'asc')->orderBy('nama_organisasi', 'asc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('logo-prodi-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(2)
                    ->parameters([
                        'scrollX' => true,
                        'language' => [
                            'search' => 'Cari:',
                            'lengthMenu' => 'Tampilkan _MENU_ data per halaman',
                            'zeroRecords' => 'Data logo organisasi tidak ditemukan',
                            'info' => 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                            'infoEmpty' => 'Tidak ada data tersedia',
                            'paginate' => [
                                'first' => 'Pertama',
                                'last' => 'Terakhir',
                                'next' => 'Selanjutnya',
                                'previous' => 'Sebelumnya',
                            ],
                        ],
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('No')->orderable(false)->searchable(false)->width(40)->addClass('text-center'),
            Column::computed('logo_preview')->title('Logo')->orderable(false)->searchable(false)->width(65)->addClass('text-center'),
            Column::computed('kategori_badge')->title('Kategori')->width(140),
            Column::computed('organisasi')->title('Nama Organisasi & Singkatan'),
            Column::make('program_studi')->title('Program Studi & Fakultas'),
            Column::make('is_active')->title('Status')->width(85)->addClass('text-center'),
            Column::computed('action')
                  ->title('Aksi')
                  ->exportable(false)
                  ->printable(false)
                  ->width(100)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'LogoOrganisasiProdi_' . date('YmdHis');
    }
}
