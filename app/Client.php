<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Transaction;

class Client extends Model
{
    /**
     * The editable properties
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'avatar', 'email',
    ];

    /**
     * The one to many relationship to transactions
     */
    public function transactions()
    {
      return $this->hasMany(Transaction::class);
    }
}
