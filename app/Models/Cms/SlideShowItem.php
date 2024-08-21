<?php

namespace App\Models\Cms;

use App\Traits\RestrictOnDelete;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class SlideShowItem extends Model
{
    use HasFactory, HasUuids, RestrictOnDelete, SoftDeletes;

    protected $table = 'slideshow_items';

    protected $guarded = ['id'];

    protected $tableName = 'item slide show';

    protected $ignoreOnDelete = ['slideshow'];

    protected $fillable = [
        'slideshow_id',
        'title',
        'caption',
        'image',
        'order',
    ];

    /**
     * Mutator for file attribute to upload file automatically
     *
     * @param  \Illuminate\Http\UploadedFile|null  $value  File to upload
     */
    public function setImageAttribute($value): void
    {
        if (gettype($value) == 'string') {
            $this->attributes['image'] = $value;

            return;
        }
        $oldValue = $this->getOriginal('image');
        if ($oldValue && Storage::exists('public/'.$oldValue)) {
            Storage::delete('public/'.$oldValue);
        }
        $path = Storage::disk('public')->put('slideshow', $value);
        $this->attributes['image'] = $path;
    }

    /**
     * Download file
     */
    public function download()
    {
        if ($this->attributes['image'] !== null) {
            $storage = Storage::disk('public');
            if (! $storage->exists($this->attributes['image'])) {
                return abort(404, 'Image not found');
            } else {
                $name = $this->attributes['title'].'.'.pathinfo($this->attributes['image'], PATHINFO_EXTENSION);

                return $storage->download($this->attributes['image'], $name);
            }
        } else {
            return abort(404, 'Image not found');
        }
    }

    public function slideshow()
    {
        return $this->belongsTo(SlideShow::class, 'slideshow_id', 'id');
    }
}
