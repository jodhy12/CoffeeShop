<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class TransactionController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('isAdmin:admin,superadmin')->only([
            'edit',
            'delete',
            'update',
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->role != 'admin' || 'superadmin') {
            $transactions = Transaction::with('products', 'member', 'user')->orderByRaw('date(date_tx) desc')->get();
        } else {
            $transactions = Transaction::with('products', 'member', 'user')
                ->whereRaw('day(date_tx) = day(curdate())')
                ->orderByRaw('date(date_tx) desc')
                ->get();
        }
        $pivotQty = TransactionDetail::selectRaw('sum(qty) as total')->groupBy('transaction_id')->get();
        return view('admin.transaction.index', compact('transactions', 'pivotQty'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $members = Member::all();
        $carts = session()->get('cart');

        // If carts null, redirect to carts page
        if (!$carts) {
            session()->flash('message', 'Fill your cart before transaction');
            session()->flash('alert-class', 'alert-danger');
            return redirect('carts');
        }

        // If carts not null, go here
        return view('admin.transaction.create', compact('carts', 'members'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name_cust' => ['required_without:member_id', 'max:64'],
            'member_id' => ['required_without:name_cust'],
            'total' => ['required'],
            'date_tx' => ['required'],
        ]);


        $data = $request->all();

        // Get Data from Cart
        $carts = session()->get('cart');

        // Get All Request Column Table Transaction
        if ($request->member_id) {
            $data['name_cust'] = null;
        } else if ($request->name_cust) {
            $data['member_id'] = null;
        }

        // Save data to Transaction
        $transaction = Transaction::create($data);

        // Attach data to Transaction Details
        foreach ($carts as $key => $cart) {
            $transaction->products()->attach($key, [
                'qty' => $cart['qty'],
                'price' => $cart['price'] * $cart['qty']
            ]);

            // Remove quantity from Product
            removeQtyProduct($key, $cart['qty']);
        }
        // Clear all cart
        $request->session()->forget('cart');

        session()->flash('message', 'Transaction Success');
        session()->flash('alert-class', 'alert-success');
        return redirect('transactions');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        $transaction = Transaction::with('products', 'member', 'user')->findOrFail($transaction->id);
        return view('admin.transaction.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }

    public function receipt(Transaction $transaction)
    {
        $txDetail = Transaction::with('products', 'member', 'user')->where('id', '=', $transaction->id)->get();
        return view('admin.transaction.receipt', compact('txDetail'));
    }
}
