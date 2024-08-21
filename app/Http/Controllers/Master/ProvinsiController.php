<?php

namespace App\Http\Controllers\Master;

use App\DataTables\Master\ProvinsiDataTable;
use App\Exceptions\RestrictDeleteException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Provinsi\StoreProvinsi;
use App\Http\Requests\Master\Provinsi\UpdateProvinsi;
use App\Models\Master\Provinsi;
use Illuminate\Support\Facades\Auth;
use ResponseFormatter;

class ProvinsiController extends Controller
{
    protected $modules = ['data-master', 'data-master.provinsi'];

    /**
     * Display a listing of the resource.
     */
    public function index(ProvinsiDataTable $dataTable)
    {
        return $dataTable->render('pages.admin.master.provinsi.index');
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
    public function store(StoreProvinsi $request)
    {
        Provinsi::create($request->all());

        return ResponseFormatter::created('Provinsi Berhasil Ditambahkan');
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
        // dd(Auth::user()->can('data-master.provinsi-read'));
        $provinsi = Provinsi::find($id);

        return response()->json($provinsi);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProvinsi $request, string $id)
    {
        $provinsi = Provinsi::find($id);
        $provinsi->update($request->all());

        return ResponseFormatter::success('Provinsi Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $provinsi = Provinsi::find($id);
            $provinsi->delete();

            return ResponseFormatter::success('Provinsi Berhasil Dihapus');
        } catch (RestrictDeleteException $e) {
            return ResponseFormatter::error($e->getMessage());
        } catch (\Throwable $e) {
            return ResponseFormatter::error('Provinsi tidak dapat dihapus', code: 500);
        }
    }

    public function restore($id)
    {
        $provinsi = Provinsi::withTrashed()->find($id);
        $provinsi->restore();

        return ResponseFormatter::success('Provinsi Berhasil Di Restore');
    }

    // public function forceDelete($id)
    // {
    //     $provinsi = Provinsi::withTrashed()->find($id);
    //     $provinsi->forceDelete();
    //     return ResponseFormatter::created("Provinsi Berhasil Di Hapus Permanent");
    // }

}
