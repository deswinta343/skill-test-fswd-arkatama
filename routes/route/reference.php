<?php

use App\Http\Controllers\ReferenceController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'reference', 'as' => 'reference.'], function () {
    Route::get('/user', [ReferenceController::class, 'user'])->name('user');
    Route::get('/icon', [ReferenceController::class, 'icon'])->name('icon');
    Route::get('/prodi', [ReferenceController::class, 'prodi'])->name('prodi');
    Route::get('/asesor', [ReferenceController::class, 'asesor'])->name('asesor');
    Route::get('/kab_kota', [ReferenceController::class, 'kab_kota'])->name('kab_kota');
    Route::get('/provinsi', [ReferenceController::class, 'provinsi'])->name('provinsi');
    Route::get('/formulir', [ReferenceController::class, 'formulir'])->name('formulir');
    Route::get('/kecamatan', [ReferenceController::class, 'kecamatan'])->name('kecamatan');
    Route::get('/matakuliah', [ReferenceController::class, 'matakuliah'])->name('matakuliah');
    Route::get('/tahun_ajaran', [ReferenceController::class, 'tahun_ajaran'])->name('tahun_ajaran');
    Route::get('/kategori_berita', [ReferenceController::class, 'kategori_berita'])->name('kategori_berita');
    Route::get('/status_administrasi/filter', [ReferenceController::class, 'status_administrasi_filter'])->name('status_administrasi.filter');
    Route::get('/status_administrasi/verify/{formulir_id}', [ReferenceController::class, 'status_administrasi_verify'])->name('status_administrasi.verify');
    Route::get('/status_kelulusan', [ReferenceController::class, 'status_kelulusan'])->name('status_kelulusan');
    Route::get('/matakuliah_asesor', [ReferenceController::class, 'matakuliah_asesor'])->name('matakuliah_asesor');
    Route::get('/matakuliah_asesor/{jenis}', [ReferenceController::class, 'matakuliah_asesor_filter'])->name('matakuliah_asesor.filter');
    Route::get('/jenjang_pendidikan', [ReferenceController::class, 'jenjang_pendidikan'])->name('jenjang_pendidikan');
    Route::get('/status_administrasi', [ReferenceController::class, 'status_administrasi'])->name('status_administrasi');
    Route::get('/channel', [ReferenceController::class, 'channel'])->name('channel');
});
