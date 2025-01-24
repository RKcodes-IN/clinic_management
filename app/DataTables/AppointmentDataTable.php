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

            ->addColumn('type', function ($row) {
                return Appointment::getTypeLabes($row->type);
            })

            ->addColumn('is_online', function ($row) {

                if ($row->is_online == 1) {
                    return 'Online';
                } else {
                    return 'Visit';
                }
            })

            ->addColumn('age', function ($row) {

                if ($row->age == 0) {
                    return $row->patient->age;
                } else {
                    return $row->age;
                }
            })

            ->filterColumn('patient_name', function ($query, $keyword) {
                $query->whereHas('patient', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%$keyword%");
                });
            })
            ->addColumn('appointment_type', '')
            ->addColumn('action', 'appointment.action')
            ->rawColumns(['status', 'type', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Appointment $model): QueryBuilder
    {
        $query = $model->newQuery()->with(['patient', 'doctor']);

        // Apply filters for confirmation_date
        $fromDate = request()->get('from_date');
        $toDate = request()->get('to_date');

        if ($fromDate && $toDate) {
            $query->whereBetween('confirmation_date', [$fromDate, $toDate]);
        } elseif ($fromDate) {
            $query->whereDate('confirmation_date', '>=', $fromDate);
        } elseif ($toDate) {
            $query->whereDate('confirmation_date', '<=', $toDate);
        }

        // Existing status filter
        $status = request()->get('status');
        if ($status) {
            $query->where('status', $status);
        }

        // Order by created_at
        $query->orderBy('created_at', 'desc');

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
                ->addClass('text-center')
                ->orderable(false), // Actions column is not sortable


            Column::make('status')
                ->defaultContent('Unknown')
                ->orderable(true), // Enable sorting

            Column::make('type')
                ->title('Type')
                ->defaultContent('Unknown')
                ->orderable(true),

            Column::make('is_online')
                ->title('Online/<br>Visit')
                ->defaultContent('Unknown')
                ->orderable(true),

            Column::make('patient_name')
                ->title('Patient<br>Name')

                ->defaultContent('N/A')
                ->orderable(true),

            Column::make('main_complaint')
                ->title('Main<br>Comp.')
                ->addClass('word-wrap')
                ->defaultContent('No Complaint')
                ->orderable(true),

            Column::make('confirmation_date')
                ->title('Conf.<br> Date')
                ->defaultContent('Not Set')
                ->orderable(true),

            Column::make('confirmation_time')
                ->title('Conf.<br>Time')
                ->defaultContent('Not Set')
                ->orderable(true),

            Column::make('available_date')
                ->title('Avl.<br>Date')

                ->defaultContent('Not Set')
                ->orderable(true),

            Column::make('time_from')
                ->title('T.<br>From')

                ->defaultContent('Not Set')
                ->orderable(true),

            Column::make('time_to')
                ->title('T.<br>To')

                ->defaultContent('Not Set')
                ->orderable(true),

            Column::make('doctor_name')
                ->title('Doctor Name')
                ->defaultContent('N/A')
                ->orderable(true),

            Column::make('email')
                ->defaultContent('No Email')
                ->orderable(true),

            Column::make('phone_number')
                ->defaultContent('No Phone Number')
                ->orderable(true),
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
