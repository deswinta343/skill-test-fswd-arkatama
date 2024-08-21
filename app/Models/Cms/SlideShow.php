<?php

namespace App\Models\Cms;

use App\Traits\RestrictOnDelete;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SlideShow extends Model
{
    use HasFactory, HasUuids, RestrictOnDelete, SoftDeletes;

    protected $table = 'slideshow';

    protected $guarded = ['id'];

    protected $tableName = 'slide show';

    protected $ignoreOnDelete = [''];

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    public function items()
    {
        return $this->hasMany(SlideShowItem::class, 'slideshow_id', 'id');
    }
}
