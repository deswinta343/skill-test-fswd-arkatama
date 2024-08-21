<?php

namespace App\Http\Controllers\Master;

use App\DataTables\Master\KategoriBeritaDataTable;
use App\Exceptions\RestrictDeleteException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\KategoriBerita\StoreKategoriBerita;
use App\Http\Requests\Master\KategoriBerita\UpdateKategoriBerita;
use App\Models\Master\KategoriBerita;
use ResponseFormatter;

class KategoriBeritaController extends Controller
{
    protected $modules = ['data-master.kategori-berita'];

    /**
     * Display a listing of the resource.
     */
    public function index(KategoriBeritaDataTable $dataTable)
    {
        return $dataTable->render('pages.admin.master.kategori-berita.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKategoriBerita $request)
    {
        KategoriBerita::create($request->all());

        return ResponseFormatter::created('Kategori Berita Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(KategoriBerita $kategoriBerita)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

        $kategori_berita = KategoriBerita::find($id);

        return response()->json($kategori_berita);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKategoriBerita $request, string $id)
    {
        $kategoriBerita = KategoriBerita::find($id);
        $kategoriBerita->update($request->all());

        return ResponseFormatter::success('Kategori Berita Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $kategoriBerita = KategoriBerita::find($id);
            $kategoriBerita->delete();

            return ResponseFormatter::success('Kategori Berita Berhasil Dihapus');
        } catch (RestrictDeleteException $e) {
            return ResponseFormatter::error($e->getMessage());
        } catch (\Throwable $e) {
            return ResponseFormatter::error('Kategori Berita tidak dapat dihapus', code: 500);
        }
    }

    public function restore($id)
    {
        $kategoriBerita = KategoriBerita::withTrashed()->find($id);
        $kategoriBerita->restore();

        return ResponseFormatter::success('Kategori Berita Berhasil Di Restore');
    }
}
