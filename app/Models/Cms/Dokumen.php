<?php

namespace App\Models\Cms;

use App\Traits\AuditChanges;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Dokumen extends Model
{
    use AuditChanges,
        HasFactory,
        HasUuids,
        SoftDeletes;

    /**
     * Property to ignore deleted by on AuditChanges trait
     */
    public bool $ignoreDeletedBy = true;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dokumen';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>|bool
     */
    protected $fillable = [
        'nama',
        'keterangan',
        'file',
    ];

    /**
     * Boot the model
     */
    protected static function boot(): void
    {
        parent::boot();

        // Register the event to remove the file when the model is force deleted
        static::forceDeleted(function ($model) {
            if (isset($model->attributes['file']) && $model->attributes['file'] !== null) {
                Storage::disk('public')->delete($model->attributes['file']);
            }
        });
    }

    /**
     * Mutator for file attribute to upload file automatically
     *
     * @param  \Illuminate\Http\UploadedFile|null  $value  File to upload
     */
    public function setFileAttribute($value): void
    {
        if (gettype($value) == 'string') {
            $this->attributes['file'] = $value;

            return;
        }
        $oldValue = $this->getOriginal('file');
        if ($oldValue && Storage::exists('public/'.$oldValue)) {
            Storage::delete('public/'.$oldValue);
        }
        $path = Storage::disk('public')->put('dokumen', $value);
        $this->attributes['file'] = $path;
    }

    /**
     * Download file
     */
    public function download(): mixed
    {
        if ($this->attributes['file'] !== null) {
            $storage = Storage::disk('public');
            if (! $storage->exists($this->attributes['file'])) {
                return abort(404, 'File not found');
            } else {
                $name = $this->attributes['nama'].'.'.pathinfo($this->attributes['file'], PATHINFO_EXTENSION);

                return $storage->download($this->attributes['file'], $name);
            }
        } else {
            return abort(404, 'File not found');
        }
    }
}
