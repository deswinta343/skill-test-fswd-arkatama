<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminFakultasController;
use App\Http\Controllers\Admin\AdminProdiController;
use App\Http\Controllers\Admin\AdminUptController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImpersonateController;
use App\Http\Controllers\Landing\LandingPageController;
use App\Http\Controllers\Landing\NewsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;

Auth::routes(
    [
        'verify' => true,
    ]
);

Route::get('/logout', function () {
    return redirect('/login');
})->name('logout');

Route::get('/end-impersonation', [ImpersonateController::class, 'leaveImpersonation'])->name('leaveImpersonation');

Route::middleware(['auth', 'verified', 'year-acedemic'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    require __DIR__.'/route/admin/setting.php';
    require __DIR__.'/route/admin/usermanagement.php';
    require __DIR__.'/route/admin/master.php';
    require __DIR__.'/route/admin/cms.php';
});

require __DIR__.'/route/reference.php';

Route::get('/', [LandingPageController::class, 'index'])->name('home');

Route::get('/unduh', [LandingPageController::class, 'unduh'])->name('unduh');
Route::get('/berita', [NewsController::class, 'index'])->name('news');
Route::get('/berita/pencarian', [NewsController::class, 'search'])->name('news.search');
Route::get('/berita/kategori/{id}', [NewsController::class, 'kategori'])->name('news.kategori');
Route::get('/berita/detail/{id}', [NewsController::class, 'show'])->name('news.detail');

Route::get('/document/download/{document}', [LandingPageController::class, 'download'])->name('document.download');

Route::prefix('chart-data')->group(function () {
    Route::get('animo-daerah', [AdminController::class, 'animoDaerah']);
    Route::get('approv-daerah', [AdminController::class, 'approvDaerah']);
    Route::get('approv-lulus', [AdminController::class, 'approvLulus']);

    Route::get('animo-daerah', [AdminUptController::class, 'animoDaerah']);
    Route::get('approv-daerah', [AdminUptController::class, 'approvDaerah']);
    Route::get('approv-lulus', [AdminUptController::class, 'approvLulus']);

    Route::get('animo-daerah', [AdminFakultasController::class, 'animoDaerah']);
    Route::get('approv-daerah', [AdminFakultasController::class, 'approvDaerah']);
    Route::get('approv-lulus', [AdminFakultasController::class, 'approvLulus']);

    Route::get('animo-daerah-prodi', [AdminProdiController::class, 'getProvinsiStats']);
    Route::get('approv-daerah-prodi', [AdminProdiController::class, 'getProvinsiApprovedStats']);
    Route::get('approv-lulus-prodi', [AdminProdiController::class, 'getMatakuliahStats']);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



Route::prefix('bookings')->group(function () {
    Route::get('/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/store', [BookingController::class, 'store'])->name('bookings.store');
});

