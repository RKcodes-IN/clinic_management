<?php

namespace App\DataTables;

use App\Models\DoctorDetail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class DoctorDetailDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'doctor-details.action')
            ->editColumn('profile_image', function ($row) {
                // Assuming the image path is stored in the 'profile_image' field
                if (!empty($row->profile_image)) {
                    $imageUrl = asset('storage/' . $row->profile_image); // Adjust the path based on your setup
                    return '<img src="' . $imageUrl . '" alt="Profile Image" width="50" height="50" class="img-thumbnail">';
                } else {
                    return '<img src="https://i.pinimg.com/280x280_RS/e1/08/21/e10821c74b533d465ba888ea66daa30f.jpg" alt="Profile Image" width="50" height="50" class="img-thumbnail">';
                }
            })
            ->editColumn('created_at', function ($row) {
                // Format the created_at column to show only the date
                return Carbon::parse($row->created_at)->format('Y-m-d');
            })
            ->rawColumns(['profile_image', 'action']) // Mark these columns as raw to render HTML
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(DoctorDetail $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('doctordetail-table')
            ->columns($this->getColumns())
            ->minifiedAjax()

            //->dom('Bfrtip')
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([

                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),

            Column::make('id'),
            Column::make('name'),
            Column::make('age'),
            Column::make('gender'),
            Column::make('education'),
            Column::make('specialty'),
            Column::make('phone'),
            Column::make('profile_image'),
            Column::make('created_at')
                ->title('Created At'),

        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'DoctorDetail_' . date('YmdHis');
    }
}
