<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupScheduleTables extends Model
{
    use HasFactory;

    protected $table = 'backup_schedule_tables';

    protected $fillable = [
        'backup_schedule_id',
        'table_name',
    ];

    public function backupSchedule()
    {
        return $this->belongsTo(BackupSchedule::class);
    }
}
