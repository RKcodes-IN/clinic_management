<?php

namespace App\DataTables;

use App\Models\Appointment;
use App\Models\AppointmentDetail;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AppointmentDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('patient_name', function ($row) {
                return $row->patient->name ?? "";
            })
            ->addColumn('doctor_name', function ($row) {
                return $row->doctor->name ?? "";
            })
            ->addColumn('status', function ($row) {
                return Appointment::getStatusLabel($row->status);
            })
            ->addColumn('action', 'appointment.action')
            ->addColumn('approve', 'appointment.approve')
            ->rawColumns(['status', 'action', 'approve'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Appointment $model): QueryBuilder
    {
        $query = $model->newQuery()->with(['patient', 'doctor']);

        // Get the status from the request
        $status = request()->get('status');

        // Apply the status filter if it exists
        if ($status) {
            $query->where('status', $status);
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('appointmentdetail-table')
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
            Column::computed('approve')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
            // Column::make('id'),
            Column::make('status')
                ->defaultContent('Unknown'),
            Column::make('patient_name')
                ->defaultContent('N/A'),
                Column::make('confirmation_date')
                ->title('Conf. Date')

                ->defaultContent('Not Set'),


                Column::make('confirmation_time')
                ->title('Conf. Time')
                ->defaultContent('Not Set'),
                Column::make('available_date')
                ->defaultContent('Not Set'),
                Column::make('time_from')
                ->defaultContent('Not Set'),
            Column::make('time_to')
                ->defaultContent('Not Set'),

                Column::make('main_complaint')
                ->defaultContent('No Complaint'),
            Column::make('doctor_name')
                ->title('Doctor Name')
                ->defaultContent('N/A'),
            Column::make('email')
                ->defaultContent('No Email'),
            Column::make('phone_number')
                ->defaultContent('No Phone Number'),



        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'AppointmentDetail_' . date('YmdHis');
    }
}
