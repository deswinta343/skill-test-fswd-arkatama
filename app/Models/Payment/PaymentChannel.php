<?php

namespace App\Models\Payment;

use App\Traits\RestrictOnDelete;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentChannel extends Model
{
    use HasFactory, HasUuids, RestrictOnDelete, SoftDeletes;

    protected array $ignoreOnDelete = ['payment_gateway'];

    protected $table = 'payment_channels';

    protected $guarded = ['id'];

    public $incrementing = false;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    protected $fillable = [
        'payment_type',
        'name',
        'kode',
        'fee_customer_flat',
        'fee_customer_percent',
        'minimum_fee',
        'maximum_fee',
        'is_active',
    ];

    public function payment_gateway()
    {
        return $this->belongsTo(PaymentGateway::class);
    }
}
