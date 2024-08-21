<?php

use App\Http\Controllers\Setting\BackupScheduleController;
use App\Http\Controllers\Setting\MenusController;
use App\Http\Controllers\Setting\MenuSortingController;
use App\Http\Controllers\Setting\SiteSettingController;
use App\Http\Controllers\Setting\SystemSettingController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'setting', 'as' => 'setting.'], function () {

    Route::get('/site-settings/history', [SiteSettingController::class, 'history'])->name('site-settings.histori');
    Route::post('/site-settings/restore/{id}', [SiteSettingController::class, 'restore'])->name('site-settings.restore');
    Route::resource('/site-settings', SiteSettingController::class)->names('site-settings');

    Route::get('/system-setting/history', [SystemSettingController::class, 'history'])->name('system-setting.histori');
    Route::post('/system-setting/restore/{id}', [SystemSettingController::class, 'restore'])->name('system-setting.restore');
    Route::resource('/system-setting', SystemSettingController::class)->names('system-setting');

    Route::post('/menu/export-json', [MenusController::class, 'exportJson'])->name('menus.exportjson');
    Route::resource('/menu', MenusController::class)->names('menus');
    Route::get('/icons/reference', [MenusController::class, 'iconsRef'])->name('icons.reference');

    Route::resource('/backup', BackupScheduleController::class, ['parameters' => ['backup' => 'backupSchedule']])
        ->names('backup');
    Route::post('/backup/{backupSchedule}/run', [BackupScheduleController::class, 'run'])->name('backup.run');
    Route::get('/backup/{backup_name}/download', [BackupScheduleController::class, 'download'])->name('backup.download');

    Route::resource('/menu-sorting', MenuSortingController::class)->only(['index', 'store']);
});
