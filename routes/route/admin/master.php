<?php

use App\Http\Controllers\Master\KabupatenController;
use App\Http\Controllers\Master\KategoriBeritaController;
use App\Http\Controllers\Master\KecamatanController;
use App\Http\Controllers\Master\ProvinsiController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'master', 'as' => 'master.'], function () {
    Route::resource('/provinsi', ProvinsiController::class)->names('provinsi')->except('create', 'show');
    Route::get('/provinsi/histori', [ProvinsiController::class, 'index'])->name('provinsi.histori');
    Route::put('/provinsi/restore/{id}', [ProvinsiController::class, 'restore'])->name('provinsi.restore');
    Route::resource('/kabupaten', KabupatenController::class)->names('kabupaten-kota')->except('create', 'show');
    Route::get('/kabupaten/histori', [KabupatenController::class, 'index'])->name('kabupaten-kota.histori');
    Route::put('/kabupaten/restore/{id}', [KabupatenController::class, 'restore'])->name('kabupaten-kota.restore');
    Route::resource('/kecamatan', KecamatanController::class)->names('kecamatan')->except('create', 'show');
    Route::get('/kecamatan/histori', [KecamatanController::class, 'index'])->name('kecamatan.histori');
    Route::put('/kecamatan/restore/{id}', [KecamatanController::class, 'restore'])->name('kecamatan.restore');
    Route::resource('/kategori-berita', KategoriBeritaController::class)->names('kategori-berita')->except('create', 'show')->parameter('kategori-berita', 'kategori-berita');
    Route::get('/kategori-berita/histori', [KategoriBeritaController::class, 'index'])->name('kategori-berita.histori');
    Route::put('/kategori-berita/restore/{id}', [KategoriBeritaController::class, 'restore'])->name('kategori-berita.restore');
});
