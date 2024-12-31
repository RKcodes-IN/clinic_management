<?php

namespace App\DataTables;

use App\Models\PatientDetail;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PaitentDetailDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('name', function ($row) {
                return strtoupper($row->name);
            })
            ->filterColumn('name', function ($query, $keyword) {
                // Debug search keyword
                $query->where('name', 'like', "%{$keyword}%");
            })
            ->addColumn('action', 'paitentdetail.action')
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(PatientDetail $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('paitentdetail-table')
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
            Column::make('name')->data('name')->defaultContent('N/A')->searchable(true),
            Column::make('phone_number')->data('phone_number')->defaultContent('N/A'),
            Column::make(data: 'gender')->data('gender')->defaultContent('Unknown'),
            Column::make('age')->data('age')->defaultContent('0'),
            Column::make('address')->data('address')->defaultContent('Not Provided'),
            Column::make('place')->title('Location')->defaultContent('Not Provided'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'PaitentDetail_' . date('YmdHis');
    }
}
