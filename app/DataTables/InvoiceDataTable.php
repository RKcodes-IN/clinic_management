<?php

namespace App\DataTables;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class InvoiceDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'invoice.action') // Add a custom action column
            ->setRowId('id')
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d M Y'); // Format the date
            })
            ->editColumn('payment_status', function (Invoice $row) {
                // Convert the payment status to a label using a helper method
                return \App\Models\Invoice::getPaymentStatusLabel($row->payment_status);
            })
            ->filterColumn('patient_name', function ($query, $keyword) {
                // Modify the query to filter the patient_name column
                $query->whereRaw('LOWER(patient_details.name) LIKE ?', ["%{$keyword}%"]);
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Invoice $model): QueryBuilder
    {
        return $model->newQuery()
            ->select([
                'invoices.id',
                'invoices.invoice_number',
                'invoices.created_at',
                'invoices.total',
                'invoices.payment_status', // Total amount from invoices table
                'patient_details.name as patient_name', // Proper aliasing for the joined table
            ])
            ->join('patient_details', 'patient_details.id', '=', 'invoices.paitent_id')->orderBy('created_at', 'desc'); // Fix typo `paitent_id` to `patient_id`
    }
    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('invoice-table')
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
            Column::make('invoice_number')->title('Invoice No.'),
            Column::make('created_at')->title('Created On'),
            Column::make('patient_name')->title('Patient Name'),
            Column::make('total')->title('Final Amount'),
            Column::make('payment_status')->title('Payment Status'), // Added payment_status column

        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Invoice_' . date('YmdHis');
    }
}