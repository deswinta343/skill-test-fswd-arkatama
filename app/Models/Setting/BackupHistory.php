<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupHistory extends Model
{
    use HasFactory;

    protected $table = 'backup_histories';

    protected $fillable = [
        'backup_schedule_id',
        'status',
        'file_name',
    ];

    public function backupSchedule()
    {
        return $this->belongsTo(BackupSchedule::class);
    }
}
