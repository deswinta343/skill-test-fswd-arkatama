<?php

namespace App\Models\Cms;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FileManagement extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'file_management';

    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'keterangan',
        'file',
        'status',
    ];

    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();
        // Delete file when model is deleted
        static::deleting(function ($model) {
            if (isset($model->attributes['file'])) {
                Storage::disk('public')->delete($model->attributes['file']);
            }
        });
    }

    /**
     * Get the user that owns the file.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function getFileAttribute(): ?String
    // {
    //     return $this->attributes["file"] ? Storage::url($this->attributes["file"]) : null;
    // }

    /**
     * Mutator for file attribute to upload file automatically
     *
     * @param  \Illuminate\Http\UploadedFile|null  $value  File to upload
     */
    public function setFileAttribute($value): void
    {
        if (gettype($value) == 'string') {
            return;
        }
        $oldValue = $this->getOriginal('file');
        if ($oldValue && Storage::exists($oldValue)) {
            Storage::delete($oldValue);
        }
        $path = Storage::disk('public')->put('files', $value);
        $this->attributes['file'] = $path;
    }

    /**
     * Download file
     */
    public function download()
    {
        if ($this->attributes['file'] !== null) {
            return Storage::download($this->attributes['file']);
        } else {
            return abort(404, 'File not found');
        }
    }

    /**
     * Get file icon attribute
     */
    public function getFileIconAttribute(): string
    {
        $ext = pathinfo($this->attributes['file'], PATHINFO_EXTENSION);
        $ext = strtolower($ext);
        $icon = 'file.png';
        switch ($ext) {
            case 'pdf':
                $icon = 'pdf.png';
                break;
            case 'doc':
            case 'docx':
                $icon = 'msword.png';
                break;
            case 'xls':
            case 'xlsx':
                $icon = 'excel.png';
                break;
            case 'ppt':
            case 'pptx':
                $icon = 'powerpoint.png';
                break;
            case 'zip':
                $icon = 'zip.png';
                break;
            case 'rar':
                $icon = 'rar.png';
                break;
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
                $icon = 'image.png';
                break;
            default:
                $icon = 'file.png';
                break;
        }

        return $icon;
    }

    /**
     * Get file type attribute
     */
    public function getFileTypeAttribute(): string
    {
        $ext = pathinfo($this->attributes['file'], PATHINFO_EXTENSION);
        $ext = strtolower($ext);
        $type = 'File';
        switch ($ext) {
            case 'pdf':
                $type = 'PDF';
                break;
            case 'doc':
            case 'docx':
                $type = 'Word';
                break;
            case 'xls':
            case 'xlsx':
                $type = 'Excel';
                break;
            case 'ppt':
            case 'pptx':
                $type = 'Powerpoint';
                break;
            case 'zip':
                $type = 'Zip';
                break;
            case 'rar':
                $type = 'Rar';
                break;
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
                $type = 'Image';
                break;
            default:
                $type = 'File';
                break;
        }

        return $type;
    }
}
