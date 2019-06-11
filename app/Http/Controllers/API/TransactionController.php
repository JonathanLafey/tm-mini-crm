<?php

namespace App\Http\Controllers\API;

use App\Transaction;
use App\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Transaction as TransactionResource;

class TransactionController extends Controller
{
    /**
     * Default constructor
     * We enable the authentication middleware for all functions of this controller
     */
    public function __construct()
    {
      $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get transactions
        // Use Laravel’s pagination for showing Clients/Transactions list, 10 entries per page
        $transactions = Transaction::paginate(10);
        return TransactionResource::collection($transactions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Use Laravel’s validation function, using Request classes
        // validate the required fields
        $request->validate([
            'transaction_date' => 'nullable|date',
            'amount' => 'required|numeric',
        ]);

        // find the parent client for the transaction
        $client = Client::find($request->client_id);
        if(!isset($client)) {
            return response()->json(['error' => 'Client not found'], 404);
        }
        // create a new transaction and add it on the client
        $transaction = new Transaction;
        // if transaction date wasn't provided, assign it the current datetime
        $transaction->transaction_date = $request->transaction_date ?? new \DateTime();
        $transaction->amount = $request->amount;
        $transaction->client_id = $client->id;

        // save it
        $transaction->save();

        return new TransactionResource($transaction);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        return new TransactionResource($transaction);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        // Use Laravel’s validation function, using Request classes
        // validate the required fields
        $request->validate([
            'transaction_date' => 'nullable|date',
            'amount' => 'required|numeric',
        ]);

        // find the parent client for the transaction
        $client = Client::find($request->client_id);
        if(!isset($client)) {
            return response()->json(['error' => 'Client not found'], 404);
        }
        // if transaction date was given, then update it on the model also
        if(isset($request->transaction_date)) {
            $transaction->transaction_date = $request->transaction_date;
        }
        $transaction->amount = $request->amount;
        $transaction->client_id = $client->id;

        // save it
        $transaction->save();

        return new TransactionResource($transaction);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return response()->json(null, 204);
    }
}
