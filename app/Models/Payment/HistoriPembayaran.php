<?php

namespace App\Models\Payment;

use App\Traits\AuditChanges;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriPembayaran extends Model
{
    use AuditChanges, HasFactory;

    /**
     * Property to ignore deleted by on AuditChanges trait
     *
     * @var bool
     */
    public $ignoreDeletedBy = true;

    protected $table = 'histori_pembayaran';

    protected $guarded = ['id'];

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }
}
