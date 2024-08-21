<?php

namespace App\Models\Master;

use App\Models\Rpl\Asesor;
use App\Traits\RestrictOnDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KabupatenKota extends Model
{
    use HasFactory, RestrictOnDelete, SoftDeletes;

    protected $table = 'ref_kabupaten_kota';

    protected $guarded = ['id'];

    public $ignoreOnDelete = ['provinsi'];

    // protected $hidden = ['provinsi_id'];

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id', 'id');
    }

    public function kecamatan()
    {
        return $this->hasMany(Kecamatan::class, 'kabupaten_kota_id', 'id');
    }

    public function asesor()
    {
        return $this->hasMany(Asesor::class, 'kabupaten', 'id');
    }
}
