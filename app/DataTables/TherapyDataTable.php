<?php

namespace App\DataTables;

use App\Models\Therapy;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TherapyDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($row) {
                return view('therapy.action', [
                    'id' => $row->therapy_id,
                    'patient_id' => $row->patient_id,
                    'date' => $row->date
                ])->render();
            })
            ->setRowId('patient_id');
    }

    
    /**
     * Get the query source of dataTable.
     */
    public function query(Therapy $model): QueryBuilder
    {
        return $model->newQuery()
            ->join('patient_details', 'therapies.patient_id', '=', 'patient_details.id')
            ->select([
                'patient_details.id as patient_id',
                'patient_details.name as name',
                \DB::raw('DATE(therapies.created_at) as date'),
                \DB::raw('MIN(therapies.id) as therapy_id'),
            ])
            ->groupBy('patient_id', 'name', 'date')
            ->orderBy('date', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('therapy-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(2, 'desc') // Order by 'date' column descending
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
            Column::make('name')->title('Patient'),
            Column::make('date')
                  ->title('Date')
                  ->type('date'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Therapy_' . date('YmdHis');
    }
}
