<?php

namespace App\Http\Controllers\Master;

use App\DataTables\Master\KecamatanDataTable;
use App\Exceptions\RestrictDeleteException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Kecamatan\StoreKecamatan;
use App\Http\Requests\Master\Kecamatan\UpdateKecamatan;
use App\Models\Master\KabupatenKota;
use App\Models\Master\Kecamatan;
use ResponseFormatter;

class KecamatanController extends Controller
{
    protected $modules = ['data-master', 'data-master.kecamatan'];

    /**
     * Display a listing of the resource.
     */
    public function index(KecamatanDataTable $dataTable)
    {
        return $dataTable->render('pages.admin.master.kecamatan.index', [
            'semua_kabupaten_kota' => KabupatenKota::all(),
        ]);
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
    public function store(StoreKecamatan $request)
    {
        Kecamatan::create($request->all());

        return ResponseFormatter::created('Kecamatan Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kecamatan = Kecamatan::find($id);

        return response()->json($kecamatan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKecamatan $request, string $id)
    {
        $kecamatan = Kecamatan::find($id);
        $kecamatan->update($request->all());

        return ResponseFormatter::success('Kecamatan Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $kecamatan = Kecamatan::find($id);
            $kecamatan->delete();

            return ResponseFormatter::success('Kecamatan Berhasil Dihapus');
        } catch (RestrictDeleteException $e) {
            return ResponseFormatter::error($e->getMessage());
        } catch (\Throwable $e) {
            return ResponseFormatter::error('Kecamatan tidak dapat dihapus', code: 500);
        }
    }

    public function restore($id)
    {
        $kecamatan = Kecamatan::withTrashed()->find($id);

        if ($kecamatan->kabupaten_kota_id) {
            $kabupaten_kota = KabupatenKota::withTrashed()->find($kecamatan->kabupaten_kota_id);
            if ($kabupaten_kota->trashed()) {
                return ResponseFormatter::error('Kecamatan tidak dapat direstore karena Kabupaten/Kota terkait sudah dihapus', code: 400);
            }
        }

        $kecamatan->restore();

        return ResponseFormatter::success('Kecamatan Berhasil Di Restore');
    }
}
