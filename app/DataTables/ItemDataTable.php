<?php

namespace App\DataTables;

use App\Models\Item;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ItemDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))

            ->addColumn('status', function ($item) {
                switch ($item->status) {
                    case 1:
                        return '<span class="badge bg-success">Active</span>';
                    case 0:
                        return '<span class="badge bg-warning">Inactive</span>';
                    case 2:
                        return '<span class="badge bg-danger">Deleted</span>';
                    default:
                        return '<span class="badge bg-secondary">Unknown</span>';
                }
            })
            ->addColumn('created_at', function ($item) {
                return \Carbon\Carbon::parse($item->created_at)->format('d M Y, h:i A');
            })
            ->addColumn('action', 'items.action')
            ->setRowId('id')

            ->rawColumns(['status', 'action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Item $model = null): QueryBuilder
    {
        $itemType = $this->request()->get('item_type');
        $model = $model ?: new Item();

        // Initialize the query
        $query = $model->newQuery()
            ->with(['category', 'brand', 'company']) // Load the relationships
            ->select('items.*'); // Select from the main table

        // Add a condition for item_type if it exists
        if ($itemType) {
            $query->where('items.item_type', $itemType);
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('item-table')
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
            Column::make('item_code'),
            Column::make('name')->title('Item Name'),
            Column::make('category.name')->title('Category')->data('category.name'), // Correct relationship path
            Column::make('brand.name')->title('Brand')->data('brand.name'),         // Correct relationship path
            Column::make('company.name')->title('Source Company')->data('company.name'), // Correct relationship path
            Column::make('status')->title('Status'),
            Column::make('created_at')->title('Created At')
        ];
    }



    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Item_' . date('YmdHis');
    }

    public function excel()
    {
        // Apply the same filtering logic for export
        $query = $this->query();

        if ($this->request()->has('item_type')) {
            $itemType = $this->request()->get('item_type');
            $query->where('item_type', $itemType);
        }

        $items = $query->with(['category', 'company', 'brand'])->get();

        return Excel::download(new \App\Exports\ItemExport($items), $this->filename() . '.xlsx');
    }
}
