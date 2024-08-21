<?php

namespace App\Models\Cms;

use App\Models\Master\KategoriBerita;
use App\Models\User;
use App\Traits\HasAuthorStamp;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class News extends Model
{
    use HasAuthorStamp, HasFactory;

    public $with = ['author', 'mutator'];

    protected $table = 'news';

    protected $fillable = [
        'title',
        'description',
        'news_kategori_id',
        'thumbnail',
        'created_by',
        'updated_by',
    ];

    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();
        // Delete file when model is deleted
        static::deleting(function ($model) {
            if (isset($model->attributes['thumbnail'])) {
                Storage::disk('public')->delete($model->attributes['thumbnail']);
            }
        });
    }

    /**
     * Mutator for file attribute to upload file automatically
     *
     * @param  \Illuminate\Http\UploadedFile|null  $value  File to upload
     */
    public function setThumbnailAttribute($value): void
    {
        if (gettype($value) == 'string') {
            return;
        }
        $oldValue = $this->getOriginal('thumbnail');
        if ($oldValue && Storage::exists($oldValue)) {
            Storage::delete('public/'.$oldValue);
        }
        $path = Storage::disk('public')->put('thumbnail', $value);
        $this->attributes['thumbnail'] = $path;
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function mutator()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriBerita::class, 'news_kategori_id');
    }
}
