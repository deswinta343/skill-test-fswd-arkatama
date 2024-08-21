<?php

namespace App\Models\Payment;

use App\Models\Akademik\ProgramStudi;
use App\Models\Rpl\Register;
use App\Traits\AuditChanges;
use App\Traits\RestrictOnDelete;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Pembayaran extends Model
{
    use AuditChanges,
        HasFactory,
        HasUuids,
        RestrictOnDelete, // check relationship before delete
        SoftDeletes;

    /**
     * Property to ignore deleted by on AuditChanges trait
     *
     * @var bool
     */
    public $ignoreDeletedBy = true;

    /**
     * Property to ignore relations check on RestrictOnDelete trait
     *
     * @var array
     */
    public $ignoreOnDelete = ['channel', 'register', 'histories', 'logs'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pembayaran';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array|bool
     */
    protected $guarded = ['id'];

    /**
     * Mutator for bukti pembayaran attribute
     *
     * @param  \Illuminate\Http\UploadedFile|null  $value  File to upload
     */
    public function setBuktiPembayaranAttribute($value): void
    {
        if (gettype($value) == 'string') {
            $this->attributes['bukti_pembayaran'] = $value;

            return;
        }
        $oldValue = $this->attributes['bukti_pembayaran'];
        if ($oldValue && Storage::exists('public/'.$oldValue)) {
            Storage::delete('public/'.$oldValue);
        }
        $path = Storage::disk('public')->put('payments', $value);
        $this->attributes['bukti_pembayaran'] = $path;
    }

    /**
     * Accessor for bukti pembayaran attribute
     *
     * @param  string|null  $value  File to upload
     */
    public function getBuktiPembayaranAttribute($value): ?array
    {
        try {
            return getFileInfo($value);
        } catch (\Throwable $e) {
            return null;
        }
    }

    /* =============================================
    =            Eloquent Relationships            =
    ============================================= */

    public function channel()
    {
        return $this->belongsTo(PaymentChannel::class, 'payment_channel_id', 'id');
    }

    public function register()
    {
        return $this->belongsTo(Register::class, 'register_id', 'id');
    }

    public function histories()
    {
        return $this->hasMany(HistoriPembayaran::class, 'pembayaran_id', 'id');
    }

    public function logs()
    {
        return $this->hasMany(LogPembayaran::class, 'pembayaran_id', 'id');
    }

    public function historiPembayaran()
    {
        return $this->hasMany(HistoriPembayaran::class, 'pembayaran_id', 'id');
    }

    public function logPembayaran()
    {
        return $this->hasMany(LogPembayaran::class, 'pembayaran_id', 'id');
    }

    public function prodi()
    {
        return $this->register()->belongsTo(ProgramStudi::class, 'prodi_id', 'id');
    }
}
