<?php

namespace App\DataTables;

use App\Models\Item;
use App\Models\Stock;
use App\Models\StockTransaction;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class StockDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('item_name', function (Stock $stock) {
                return $stock->item->name ?? 'N/A'; // Replace 'name' with your item's name column
            })

            ->editColumn('item.item_type', function (Stock $stock) {
                return (new Item())->getTypeLabel($stock->item->item_type); // Use accessor
            })
            ->addColumn('item_code', function (Stock $stock) {
                return $stock->item->item_code ?? 'N/A'; // Replace 'item_code' with your item's code column
            })
            ->addColumn('total_stock', function (Stock $stock) {
                return $stock->getTotalStock($stock->item_id); // Call method in Stock model
            })
            ->addColumn('action', 'stock.action')
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    /**
     * Get the query source of dataTable.
     */
    public function query(Stock $model): QueryBuilder
    {
        $query = $model->newQuery()->with('item'); // Load the related 'item' table

        // Check for 'item_type' in the request and filter accordingly
        if ($this->request()->has('item_type')) {
            $itemType = $this->request()->get('item_type');
            $query->whereHas('item', function ($q) use ($itemType) {
                $q->where('item_type', $itemType); // Filter based on item_type
            });
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('stock-table')
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
                Button::make('reload'),
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

            Column::make('item_name')->title('Item Name'), // Custom title for clarity
            Column::make('item_code')->title('Item Code'), // Custom title for clarity
            Column::make('item_price')->title('Price'), // Custom title for clarity
            Column::make('item.item_type')->title('Item Type'),
            Column::make('total_stock')->data('total_stock')->title('Total Stock'),
            Column::make('expiry_date'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Stock_' . date('YmdHis');
    }
}
