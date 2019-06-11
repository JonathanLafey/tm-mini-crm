<?php

namespace App\Http\Controllers\API;

use App\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Client as ClientResource;

class ClientController extends Controller
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
        // Get clients
        // Use Laravel’s pagination for showing Clients/Transactions list, 10 entries per page
        $clients = Client::paginate(10);
        return ClientResource::collection($clients);
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
        // validate the required fields and the avatar image type and sizes
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'avatar' => 'required|image|mimes:jpeg,png,jpg,svg|dimensions:min_width=100,min_height=100|max:2048',
            'email' => 'required|email',
        ]);
        // create a filename which includes php's UUID (not v4) plus entropy, word 'avatar', current timestamp and the extension of the uploaded file
        $fileName = uniqid('', true).'_avatar'.time().'.'.request()->avatar->getClientOriginalExtension();

        // create the new client
        $client = new Client;
        $client->first_name = $request->first_name;
        $client->last_name = $request->last_name;
        $client->avatar = $fileName;
        $client->email = $request->email;

        // save it
        $client->save();

        // and only if save was successful, save the avatar file also, so we don't fill our storage with useless images
        $request->avatar->storeAs('',$fileName);

        return new ClientResource($client);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        return new ClientResource($client);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        // Use Laravel’s validation function, using Request classes
        // validate the required fields and the avatar image type and sizes
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'avatar' => 'required|image|mimes:jpeg,png,jpg,svg|dimensions:min_width=100,min_height=100|max:2048',
            'email' => 'required',
        ]);
        // create a filename which includes php's UUID (not v4) plus entropy, word 'avatar', current timestamp and the extension of the uploaded file
        $fileName = uniqid('', true).'_avatar'.time().'.'.request()->avatar->getClientOriginalExtension();

        // $book->update($request->only(['title', 'description']));
        $client->first_name = $request->first_name;
        $client->last_name = $request->last_name;
        $client->avatar = $request->avatar;
        $client->email = $request->email;
        $client->save();

        // and only if save was successful, save the avatar file also, so we don't fill our storage with useless images
        $request->avatar->storeAs('',$fileName);

        return new ClientResource($client);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return response()->json(null, 204);
    }
}
