<?php

namespace App\Models\Cms;

use App\Models\Akademik\TahunAjaran;
use App\Models\User;
use App\Traits\AuditChanges;
use App\Traits\RestrictOnDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Timeline extends Model
{
    use AuditChanges, HasFactory, RestrictOnDelete, SoftDeletes;

    // public $with = ['author', 'mutator'];

    protected $table = 'timeline_aktivitas';

    protected array $ignoreOnDelete = ['author', 'mutator', 'deletedBy', 'tahun_ajaran'];

    protected $fillable = [
        'tahun_ajaran_id',
        'tanggal_mulai_pendaftaran',
        'tanggal_selesai_pendaftaran',
        'tanggal_mulai_administrasi',
        'tanggal_selesai_administrasi',
        'tanggal_mulai_assesmen',
        'tanggal_seleksi_evaluasi_diri',
        'created_by',
        'updated_by',
    ];

    public function tahun_ajaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
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
