<?php

namespace App\Models\Cms;

use App\Models\User;
use App\Traits\AuditChanges;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FAQs extends Model
{
    use AuditChanges, HasFactory, SoftDeletes;

    // public $with = ['author', 'mutator'];

    protected $table = 'faqs';

    protected $fillable = [
        'question',
        'answer',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function mutator()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
