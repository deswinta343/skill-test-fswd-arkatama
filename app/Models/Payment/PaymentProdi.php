<?php

namespace App\Models\Payment;

use App\Models\Akademik\ProgramStudi;
use App\Models\Akademik\TahunAjaran;
use App\Models\User;
use App\Traits\AuditChanges;
use App\Traits\RestrictOnDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentProdi extends Model
{
    use AuditChanges, HasFactory, RestrictOnDelete, SoftDeletes;

    // public $with = ['author', 'mutator'];

    protected array $ignoreOnDelete = ['prodi', 'tahun_ajaran', 'author', 'mutator', 'deletedBy'];

    protected $table = 'payment_prodi_settings';

    protected $guarded = ['id'];

    public $incrementing = false;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    protected $fillable = [
        'prodi_id',
        'tahun_ajaran_id',
        'is_free_ukt',
        'biaya_ukt',
        'biaya_pendaftaran',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'prodi_id', 'id');
    }

    public function tahun_ajaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id', 'id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function mutator()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
