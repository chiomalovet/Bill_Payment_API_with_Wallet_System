<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElectricityPayment extends Model
{
    protected $fillable = ['service_provider', 'meter_number', 'amount', 'status', 'api_response', 'user_id'];

    protected $casts = [
        'api_response' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
