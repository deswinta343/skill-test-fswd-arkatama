<?php

use App\Http\Controllers\ImpersonateController;
use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('users')->as('users.')->group(function () {
    Route::post('/user-list/reset-password/{user}', [UserController::class, 'resetPassword'])->name('user-list.reset-password');
    Route::resource('/user-list', UserController::class)->names('user-list');
    Route::resource('/role', RoleController::class);
    Route::post('role/permission/exportjson', [RoleController::class, 'exportJson'])->name('permission.export');
    Route::put('/role/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('role.permissions');

    Route::get('/impersonate/{user}', [ImpersonateController::class, 'impersonate'])->name('impersonate');
});
