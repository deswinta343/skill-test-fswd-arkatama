<?php

namespace App\Models\Master;

use App\Models\Rpl\Asesor;
use App\Traits\RestrictOnDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kecamatan extends Model
{
    use HasFactory, RestrictOnDelete, SoftDeletes;

    protected $table = 'ref_kecamatan';

    protected $guarded = ['id'];

    public $ignoreOnDelete = ['kabKota'];

    public function kabKota()
    {
        return $this->belongsTo(KabupatenKota::class, 'kabupaten_kota_id', 'id');
    }

    public function asesor()
    {
        return $this->hasMany(Asesor::class, 'kecamatan', 'id');
    }
}
