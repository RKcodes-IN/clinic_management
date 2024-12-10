<?php

namespace App\Exports;

use App\Models\Stock;
use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class StockExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $transactions;

    public function __construct($transactions)
    {
        $this->transactions = $transactions;
    }
    public function view(): View
    {
        return view('exports.stock_report', [
            'transactions' => $this->transactions,
        ]);
    }
}
