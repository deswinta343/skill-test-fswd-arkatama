<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menus extends Model
{
    use HasFactory;

    protected $with = ['childrens'];

    protected $table = 'menus';

    protected $fillable = [
        'name',
        'module',
        'slug',
        'icon',
        'url',
        'parent_id',
        'order',
        'is_active',
        'location',
    ];

    public function childrens()
    {
        return $this->hasMany(Menus::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(Menus::class, 'parent_id', 'id');
    }

    public function accesses()
    {
        return $this->hasMany(Access::class, 'menus_id', 'id');
    }
}
