<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<User> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('name', function ($user) {
                return '<div class="d-flex align-items-center gap-2">
                    <img src="' . e($user->avatar_url) . '" class="rounded-circle shadow-sm" style="width: 32px; height: 32px; object-fit: cover; flex-shrink: 0; border: 1px solid #e2e8f0;">
                    <div>
                        <div class="fw-semibold text-dark">' . e($user->name) . '</div>
                        ' . ($user->nim ? '<small class="text-muted">NIM: ' . e($user->nim) . '</small>' : '') . '
                    </div>
                </div>';
            })
            ->editColumn('role', function ($user) {
                $badgeClasses = [
                    'superadmin'       => 'bg-danger',
                    'adminbkak'        => 'bg-warning text-dark',
                    'kabid'            => 'bg-info text-dark',
                    'staff'            => 'bg-primary',
                    'pimpinan'         => 'bg-dark',
                    'prodi'            => 'bg-secondary',
                    'dosenpendamping'  => 'bg-success',
                    'mahasiswa'        => 'bg-info',
                ];
                $bgClass = $badgeClasses[$user->role] ?? 'bg-secondary';
                return '<span class="badge ' . $bgClass . '">' . e(strtoupper($user->role)) . '</span>';
            })
            ->editColumn('is_active', function ($user) {
                if ($user->is_active) {
                    return '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Aktif</span>';
                }
                return '<span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i> Non-Aktif</span>';
            })
            ->addColumn('action', function ($user) {
                $editUrl = route('users.edit', $user->id);
                $deleteUrl = route('users.destroy', $user->id);
                $csrf = csrf_field();
                $method = method_field('DELETE');

                $btnEdit = '<a href="' . $editUrl . '" class="btn-action btn-action-edit" title="Edit Pengguna"><i class="bi bi-pencil-square"></i></a>';
                
                if (Auth::id() === $user->id) {
                    $btnDelete = '<button type="button" class="btn-action disabled" title="Tidak dapat menghapus akun sendiri" disabled><i class="bi bi-trash"></i></button>';
                } else {
                    $btnDelete = '
                        <form action="' . $deleteUrl . '" method="POST" class="d-inline m-0 p-0 delete-form">
                            ' . $csrf . '
                            ' . $method . '
                            <button type="button" class="btn-action btn-action-delete btn-delete" data-id="' . $user->id . '" title="Hapus Pengguna"><i class="bi bi-trash"></i></button>
                        </form>';
                }

                return '<div class="action-btn-group" role="group">' . $btnEdit . $btnDelete . '</div>';
            })
            ->rawColumns(['name', 'role', 'is_active', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<User>
     */
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('users-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1)
                    ->parameters([
                        'scrollX' => true,
                        'language' => [
                            'search' => 'Cari:',
                            'lengthMenu' => 'Tampilkan _MENU_ data per halaman',
                            'zeroRecords' => 'Data pengguna tidak ditemukan',
                            'info' => 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                            'infoEmpty' => 'Tidak ada data tersedia',
                            'paginate' => [
                                'first' => 'Pertama',
                                'last' => 'Terakhir',
                                'next' => 'Selanjutnya',
                                'previous' => 'Sebelumnya'
                            ]
                        ]
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('No')->orderable(false)->searchable(false)->width(40),
            Column::make('name')->title('Nama Lengkap'),
            Column::make('email')->title('Email'),
            Column::make('role')->title('Role'),
            Column::make('is_active')->title('Status'),
            Column::computed('action')
                  ->title('Aksi')
                  ->exportable(false)
                  ->printable(false)
                  ->width(120)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Users_' . date('YmdHis');
    }
}
