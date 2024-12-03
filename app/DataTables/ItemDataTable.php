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
            ->addColumn('action', 'items.action')
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */ public function query(Item $model = null): QueryBuilder
    {
        $model = $model ?: new Item();
        return $model->newQuery()
            ->with(['category', 'company', 'brand']);
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
            Column::make('name')->title('Item Name'),  // Main item name
            Column::make('category.name')  // Column for category name
                ->title('Category')
                ->searchable(true)  // Optional: Enable search based on category name
                ->sortable(true),   // Optional: Enable sorting
            Column::make('company.name')  // Column for source company name
                ->title('Source Company')
                ->searchable(true)
                ->sortable(true),
            Column::make('brand.name')  // Column for brand name
                ->title('Brand')
                ->searchable(true)
                ->sortable(true),
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
        $items = $this->query()->with(['category', 'company', 'brand'])->get(); // Ensure relationships are loaded
        return Excel::download(new \App\Exports\ItemExport($items), $this->filename() . '.xlsx');
    }
}
