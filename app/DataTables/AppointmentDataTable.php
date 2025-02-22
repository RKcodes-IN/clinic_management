<?php

namespace App\DataTables;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

class AppointmentDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from the query() method.
     * @return EloquentDataTable
     */

    protected $fromDate;
    protected $toDate;


    public function withFilters($fromDate = null, $toDate = null)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        return $this;
    }
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('patient_name', function ($row) {
                return $row->patient_name ?? "";
            })
            ->addColumn('action', 'appointment.action')

            // Use backticks to ensure proper SQL parsing
            ->orderColumn('patient_name', '`patient_details`.`name` $1')
            ->addColumn('doctor_name', function ($row) {
                return $row->doctor_name ?? "";
            })
            ->orderColumn('doctor_name', '`doctor_details`.`name` $1')
            ->addColumn('status', function ($row) {
                return Appointment::getStatusLabel($row->status);
            })
            ->orderColumn('status', 'appointments.status $1')
            ->addColumn('type', function ($row) {
                return Appointment::getTypeLabes($row->type);
            })
            ->orderColumn('type', 'appointments.type $1')
            ->setRowId('id')
            ->rawColumns(['status', 'type', 'action']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @param Appointment $model
     * @return QueryBuilder
     */
    public function query(Appointment $model): QueryBuilder
    {
        $query = $model->newQuery()
            ->select([
                'appointments.*',
                'patient_details.name as patient_name',
                'doctor_details.name as doctor_details',
            ])
            ->leftJoin('patient_details', 'appointments.patient_id', '=', 'patient_details.id')
            ->leftJoin('doctor_details', 'appointments.doctor_id', '=', 'doctor_details.id');

        // Apply date filters if they exist
        if ($this->fromDate) {
            $query->whereDate('appointments.available_date', '>=', $this->fromDate);
        }
        if ($this->toDate) {
            $query->whereDate('appointments.available_date', '<=', $this->toDate);
        }

        return $query;
    }

    /**
     * Optional method if you want to use the HTML builder.
     *
     * @return HtmlBuilder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('appointmentdetail-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0) // Default ordering by the first column (adjust as needed)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload'),
            ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center')
                ->orderable(false),
            Column::make('status')->orderable(true),
            Column::make('type')->title('Type')->orderable(true),
            Column::make('patient_name')->title('Patient Name')->orderable(true),
            Column::make('doctor_name')->title('Doc. Name')->orderable(true),
            Column::make('confirmation_date')->title('conf. Date')->orderable(true),
            Column::make('confirmation_time')->title('conf. Time')->orderable(true),
            Column::make('available_date')->title('Avl Date')->orderable(true),
            Column::make('time_from')->title('Time From')->orderable(true),
            Column::make('time_to')->title('Time To')->orderable(true),
            Column::make('email')->title('Email')->orderable(true),
            Column::make('phone_number')->title('Phone')->orderable(true),
        ];
    }

    /**
     * Get the filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'AppointmentDetail_' . date('YmdHis');
    }
}
