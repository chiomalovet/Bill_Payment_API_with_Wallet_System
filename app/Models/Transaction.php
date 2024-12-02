<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionFactory> */
    use HasFactory;
    
    protected $fillable = ['wallet_id', 'amount', 'type', 'description', 'user_id','meter_number', 'status','meter_number','service_provider',];

    // Define the relationship with the Wallet model
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
