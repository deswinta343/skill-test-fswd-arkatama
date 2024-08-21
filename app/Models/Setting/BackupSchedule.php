<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupSchedule extends Model
{
    use HasFactory;

    protected $table = 'backup_schedules';

    protected $fillable = [
        'name',
        'frequency',
        'time',
    ];

    public function backupHistories()
    {
        return $this->hasMany(BackupHistory::class);
    }

    public function backupTables()
    {
        return $this->hasMany(BackupScheduleTables::class);
    }
}
