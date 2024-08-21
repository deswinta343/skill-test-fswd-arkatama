<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemSettingModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'system_settings';

    protected $fillable = [
        'name',
        'value',
        'description',
        'is_active',
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];
}
