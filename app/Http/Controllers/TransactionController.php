<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
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
        $transaction = Transaction::with(['food', 'user'])
            ->paginate(10);

        return view('transactions.index', [
            'transactions' => $transaction,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

        Transaction::create($data);

        return redirect()->route('transactions.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        return view('transactions.detail', [
            'item' => $transaction
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Transaction  $transaction
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
     * @param  Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        $data = $request->all();

        $transaction->update($data);

        return redirect()->route('transactions.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->route('transactions.index');
    }

    public function changeStatus(Request $request, $id, $status)
    {
        $transaction = Transaction::findOrFail($id);

        $transaction->status = $status;
        $transaction->save();

        return redirect()->route('transactions.show', $id);
    }
}
