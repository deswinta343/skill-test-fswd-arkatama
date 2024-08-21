<?php

namespace App\Models\Master;

use App\Models\Rpl\Asesor;
use App\Models\Rpl\Register;
use App\Traits\RestrictOnDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provinsi extends Model
{
    use HasFactory, RestrictOnDelete, SoftDeletes;

    protected $table = 'ref_provinsi';

    protected $guarded = ['id'];

    public $ignoreOnDelete = [''];

    public function kabKota()
    {
        return $this->hasMany(KabupatenKota::class, 'provinsi_id', 'id');
    }

    public function register()
    {
        return $this->hasMany(Register::class, 'provinsi_id', 'id');
    }

    public function asesor()
    {
        return $this->hasMany(Asesor::class, 'provinsi', 'id');
    }
}
