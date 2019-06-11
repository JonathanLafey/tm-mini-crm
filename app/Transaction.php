<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Client;

class Transaction extends Model
{
    /**
     * The editable properties
     *
     * @var array
     */
    protected $fillable = [
        'client_id', 'transaction_date', 'amount',
    ];

    /**
     * The many to one relationship to client
     */
    public function client()
    {
      return $this->belongsTo(Client::class);
    }
}
