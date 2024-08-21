<?php

namespace App\Models\Payment;

use App\Models\Akademik\TahunAjaran;
use App\Traits\RestrictOnDelete;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentGatewayOption extends Model
{
    use HasFactory, HasUuids, RestrictOnDelete, SoftDeletes;

    protected array $ignoreOnDelete = ['tahun_ajaran', 'payment_gateway'];

    protected $table = 'payment_gateway_options';

    protected $guarded = ['id'];

    public $incrementing = false;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    protected $fillable = [
        'tahun_ajaran_id',
        'payment_gateway_id',
        'is_active',
    ];

    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->deactivateAll();
            $model->is_active = '1';
        });
    }

    public function deactivateAll($except = null)
    {
        self::where('id', '!=', $except)->update(['is_active' => '0']);
    }

    public function tahun_ajaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id', 'id');
    }

    public function payment_gateway()
    {
        return $this->belongsTo(PaymentGateway::class, 'payment_gateway_id', 'id');
    }
}
