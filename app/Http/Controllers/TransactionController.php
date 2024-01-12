<?php

namespace App\Http\Controllers;

use App\Exports\TransactionsExport;
use App\Http\Filters\TransactionFilter;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    public function transactions(TransactionFilter $filter)
    {
        $transactions = Transaction::filter($filter)->with('user:id,username', 'order:id,provider_service_id', 'order.provider_service:id,title')->paginate(request()->get('limit', 15))->withQueryString();

        return view('transaction.index', compact('transactions'));
    }

    public function export()
    {
        return Excel::download(new TransactionsExport, 'transactions.xlsx');
    }
}
