<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('isAdmin:admin');
    }

    public function daily()
    {
        return view('admin.report.daily');
    }

    public function apiDaily(Request $request)
    {
        $date =  '';

        if ($request->date) {
            $date = $request->date;
        } else {
            $date = dateDbGMT7(date('Y-m-d H:i:s'));
        }
        $txDetails = TransactionDetail::from('transaction_details as td')
            ->selectRaw('td.transaction_id as tx_id, p.name, date(date_tx) as date, time(date_tx) as time, p.price, td.qty, td.price as totalPayment')
            ->join('products as p', 'p.id', '=', 'td.product_id')
            ->join('transactions as t', 't.id', '=', 'td.transaction_id')
            ->whereDate('date_tx', '' . $date . '')
            ->get();

        $totalIncome = Transaction::selectRaw('sum(total) as totalPerDay')
            ->whereDate('date_tx', '' . $date . '')
            ->pluck('totalPerDay');

        $datatables = datatables()->of($txDetails)->addIndexColumn()
            ->with('total', intval($totalIncome[0]));

        return $datatables->make(true);
    }

    public function monthly()
    {
        return view('admin.report.monthly');
    }

    public function apiMonthly(Request $request)
    {
        $month =  '';

        if ($request->month) {
            $month = date_parse($request->month);
        } else {
            $month = date_parse(monthGMT7(date('Y-m-d H:i:s')));
        }

        // $txDetails = TransactionDetail::from('transaction_details as td')
        //     ->selectRaw('date(t.date_tx) as date, p.name, p.price, td.qty as totalQty, (td.qty * p.price) as totalPayment')
        //     ->join('products as p', 'p.id', '=', 'td.product_id')
        //     ->join('transactions as t', 't.id', '=', 'td.transaction_id')
        //     ->whereMonth('date_tx', '' . $month['month'] . '')
        //     ->get();

        $txDetails = Transaction::selectRaw('id, date(date_tx) as date, sum(total) as total, count(date(date_tx)) as many_tx')
            ->whereMonth('date_tx', '' . $month['month'] . '')
            ->groupByRaw('date(date_tx)')
            ->get();


        $totalIncome = Transaction::selectRaw('sum(total) as totalPerMonth')
            ->whereMonth('date_tx', '' . $month['month'] . '')
            ->pluck('totalPerMonth');


        $datatables = datatables()->of($txDetails)->addIndexColumn()
            ->with('total', intval($totalIncome[0]));

        return $datatables->make(true);
    }
}
