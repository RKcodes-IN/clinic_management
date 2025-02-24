<?php

namespace App\DataTables;

use App\Models\LabPrescription;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class LabPrescriptionDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($row) {
                return view('lab-prescription.action', ['id' => $row->id])->render();
            })
            ->addColumn('report_available', function ($row) {
                return $row->report_available_statuses ? implode(', ', array_map('ucfirst', explode(',', $row->report_available_statuses))) : 'No';
            })
            ->rawColumns(['report_urls', 'action'])
            ->setRowId('id');
    }

    public function query(LabPrescription $model): QueryBuilder
    {
        return $model->newQuery()
            ->join('patient_details', 'lab_prescriptions.patient_id', '=', 'patient_details.id')
            ->select([
                'lab_prescriptions.id',  // actual lab prescription id
                'patient_details.name as name',
                'lab_prescriptions.date',
                \DB::raw('GROUP_CONCAT(lab_prescriptions.item_id SEPARATOR ", ") as item_ids'),
                \DB::raw('GROUP_CONCAT(lab_prescriptions.description SEPARATOR ", ") as descriptions'),
                \DB::raw('GROUP_CONCAT(lab_prescriptions.sample_taken SEPARATOR ", ") as sample_taken_statuses'),
                \DB::raw('GROUP_CONCAT(lab_prescriptions.report_available SEPARATOR ", ") as report_available_statuses'),
                \DB::raw('GROUP_CONCAT(lab_prescriptions.report_url SEPARATOR ", ") as report_urls'),
                \DB::raw('GROUP_CONCAT(lab_prescriptions.out_of_range SEPARATOR ", ") as out_of_range_statuses'),
            ])
            ->groupBy('lab_prescriptions.id', 'patient_details.name', 'lab_prescriptions.date')
            ->orderBy('lab_prescriptions.date', 'desc');
    }
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('labprescription-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(2) // Default sort by date column (index 2)
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

    public function getColumns(): array
    {
        return [
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
            Column::make('id')->title('ID'),
            Column::make('name')->title('Patient')->name('patient_details.name'),
            Column::make('date')
                ->title('Date')
                ->type('date'),

        ];
    }

    protected function filename(): string
    {
        return 'LabPrescription_' . date('YmdHis');
    }
}
