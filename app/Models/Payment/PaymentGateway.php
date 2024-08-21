<?php

namespace App\Models\Payment;

use App\Traits\RestrictOnDelete;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentGateway extends Model
{
    use HasFactory, HasUuids, RestrictOnDelete, SoftDeletes;

    protected $table = 'payment_gateways';

    protected $fillable = [
        'name',
        'helper',
    ];

    public $breadcrumbLabelCol = 'name';

    public function configs()
    {
        return $this->hasMany(PaymentGatewayConfig::class);
    }

    public function channels()
    {
        return $this->hasMany(PaymentChannel::class);
    }
}
