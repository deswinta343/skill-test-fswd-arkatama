<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGatewayConfig extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'payment_gateway_configs';

    protected $fillable = [
        'payment_gateway_id',
        'key',
        'value',
    ];

    public function payment_gateway()
    {
        return $this->belongsTo(PaymentGateway::class);
    }
}
