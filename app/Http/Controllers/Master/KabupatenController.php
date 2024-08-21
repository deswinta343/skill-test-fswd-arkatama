<?php

namespace App\Http\Controllers\Master;

use App\DataTables\Master\KabupatenKotaDataTable;
use App\Exceptions\RestrictDeleteException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Kabupaten\StoreKabupaten;
use App\Http\Requests\Master\Kabupaten\UpdateKabupaten;
use App\Models\Master\KabupatenKota;
use App\Models\Master\Provinsi;
use ResponseFormatter;

class KabupatenController extends Controller
{
    protected $modules = ['data-master', 'data-master.kabupaten'];

    /**
     * Display a listing of the resource.
     */
    public function index(KabupatenKotaDataTable $dataTable)
    {
        return $dataTable->render('pages.admin.master.kabupaten.index', ['semua_provinsi' => Provinsi::all()]);
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
    public function store(StoreKabupaten $request)
    {
        KabupatenKota::create($request->all());

        return ResponseFormatter::created('Kabupaten Berhasil Ditambahkan');
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
        $kabupatenkota = KabupatenKota::find($id);

        return response()->json($kabupatenkota);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKabupaten $request, string $id)
    {
        $kabupatenkota = KabupatenKota::find($id);
        $kabupatenkota->update($request->all());

        return ResponseFormatter::success('Kabupaten Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $kabupatenkota = KabupatenKota::find($id);
            $kabupatenkota->delete();

            return ResponseFormatter::success('Kabupaten/Kota Berhasil Dihapus');
        } catch (RestrictDeleteException $e) {
            return ResponseFormatter::error($e->getMessage());
        } catch (\Throwable $e) {
            return ResponseFormatter::error('Kabupaten/Kota tidak dapat dihapus', code: 500);
        }
    }

    public function restore($id)
    {
        $kabupatenkota = KabupatenKota::withTrashed()->find($id);

        if ($kabupatenkota->provinsi_id) {
            $provinsi = Provinsi::withTrashed()->find($kabupatenkota->provinsi_id);
            if ($provinsi->trashed()) {
                return ResponseFormatter::error('Kabupaten tidak dapat direstore karena Provinsi terkait sudah dihapus', code: 400);
            }
        }

        $kabupatenkota->restore();

        return ResponseFormatter::success('Kabupaten/Kota Berhasil Di Restore');
    }
}
