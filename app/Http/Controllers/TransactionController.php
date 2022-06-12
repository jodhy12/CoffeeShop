<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $data = $request->all();

        // Get Data in Cart
        $carts = session('cart');

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
                'price' => $cart['price']
            ]);

            // Remove quantity from Product
            removeQtyProduct($key, $cart['qty']);
        }

        // Clear all cart
        $request->session()->forget('cart');

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
        //
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
}
