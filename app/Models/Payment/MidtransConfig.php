<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MidtransConfig extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'midtrans_config';

    protected $fillable = [
        'production_merchant_id',
        'production_client_key',
        'production_server_key',
        'production_snap_url',
        'sandbox_merchant_id',
        'sandbox_client_key',
        'sandbox_server_key',
        'sandbox_snap_url',
        'is_production',
        'is_sanitized',
        'is_3ds',
    ];

    public static function config()
    {
        return self::first();
    }
}
