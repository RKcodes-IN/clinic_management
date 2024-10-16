<?php

namespace App\DataTables;

use App\Models\HealthEvaluation;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class HealthEvaluationDataTable extends DataTable
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
                return $row->patient ? $row->patient->name : 'N/A'; // Display patient name if exists
            })
            ->addColumn('action', 'health-evalution.action')
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(HealthEvaluation $model): QueryBuilder
    {
        // Eager load the 'patient' relationship
        return $model->newQuery()->with('patient');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('healthevaluation-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
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
            Column::make('name')->title('Patient Name'), // Display patient's name
            Column::make('age'), // Display patient's name
            Column::make('weight'), // Display patient's name
            Column::make('height'), // Display patient's name
            Column::make('occupation'), // Display patient's name
            Column::make('gender'), // Display patient's name
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'HealthEvaluation_' . date('YmdHis');
    }
}
