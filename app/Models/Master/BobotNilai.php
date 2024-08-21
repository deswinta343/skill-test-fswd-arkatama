<?php

namespace App\Models\Master;

use App\Traits\RestrictOnDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BobotNilai extends Model
{
    use HasFactory, RestrictOnDelete, SoftDeletes;

    protected $table = 'ref_bobot_nilai';

    protected $guarded = ['id'];

    public static function getNilaiHuruf($nilaiAngka)
    {
        $bobotNilai = self::where('nilai_min', '<=', $nilaiAngka)
            ->where('nilai_max', '>=', $nilaiAngka)
            ->first();

        return $bobotNilai ? $bobotNilai->nilai_huruf : null;
    }
}
