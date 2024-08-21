<?php

namespace App\Models\Master;

use App\Models\Akademik\ProgramStudi;
use App\Traits\RestrictOnDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenjangPendidikan extends Model
{
    use HasFactory, RestrictOnDelete, SoftDeletes;

    protected $table = 'ref_jenjang_pendidikan';

    protected $guarded = ['id'];

    public function prodi()
    {
        return $this->hasMany(ProgramStudi::class, 'jenjang_pendidikan_id', 'id');
    }
}
