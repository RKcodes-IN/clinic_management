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

class AppointmentswaDataTable extends DataTable
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

            ->addColumn('gender', function ($row) {
                return $row->patient->gender ?? "";
            })
            ->addColumn('age', function ($row) {
                return $row->patient->age ?? "";
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
                    return $row->patient->age ?? "NA";
                } else {
                    return $row->age ?? "NA";
                }
            })
            ->addColumn('action', 'appointment.action')
            ->addColumn('approve', 'appointment.approve')
            ->rawColumns(['status', 'type', 'action', 'approve'])
            ->setRowId('id')

            ->filterColumn('age', function ($query, $keyword) {
                $query->whereHas('patient', function ($q) use ($keyword) {
                    $q->where('age', 'like', "%$keyword%");
                });
            })

            ->filterColumn('patient_name', function ($query, $keyword) {
                $query->whereHas('patient', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%$keyword%");
                });
            });
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

        $query->where('status', Appointment::STATUS_CONFIRMED);

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
            ->setTableId('appointmentdetail-wa-table')
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


            // Column::make('id'),

            Column::make('patient_name')
                ->defaultContent('N/A')
                ->title('Patient Name'),
            Column::make('main_complaint')
                ->defaultContent('No Complaint'),
            Column::make('type')
                ->defaultContent('New')
                ->title('Type'),
            Column::make('gender')
                ->defaultContent('Gender'),
            Column::make('age')
                ->title('Age')
                ->defaultContent('N/A'),

            Column::make('confirmation_date')
                ->title('Conf. Date')

                ->defaultContent('Not Set'),
            Column::make('confirmation_time')
                ->title('Conf. Time')
                ->defaultContent('Not Set'),
            Column::make('is_online')
                ->title('Online/Visit')
                ->defaultContent('Not Set'),
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
