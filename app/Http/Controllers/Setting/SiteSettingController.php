<?php

namespace App\Http\Controllers\Setting;

use App\DataTables\Setting\SiteSettingDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\SiteSetting\SiteSettingStoreRequest;
use App\Models\Setting\SiteSetting;
use ResponseFormatter;

class SiteSettingController extends Controller
{
    protected $modules = ['setting', 'settings.site-setting'];

    public function index(SiteSettingDataTable $dataTable)
    {
        return $dataTable->render('pages.admin.setting.site-settings.index');
    }

    public function store(SiteSettingStoreRequest $request)
    {
        try {
            $res = SiteSetting::create($request->validated());

            return ResponseFormatter::created('Berhasil menambahkan site setting', $res);
        } catch (\Throwable $th) {
            return ResponseFormatter::error('Gagal menambahkan site setting, server error', [
                'errorMsg' => $th->getMessage(),
            ]);
        }
    }

    public function edit(SiteSetting $siteSetting)
    {
        return response()->json($siteSetting);
    }

    public function update(SiteSettingStoreRequest $request, SiteSetting $siteSetting)
    {
        try {
            $siteSetting->updateOrFail($request->validated());

            return ResponseFormatter::success('Berhasil mengupdate site setting');
        } catch (\Throwable $th) {
            return ResponseFormatter::error('Gagal mengupdate site setting, server error', [
                'errorMsg' => $th->getMessage(),
            ]);
        }
    }

    public function destroy(SiteSetting $siteSetting)
    {
        try {
            $siteSetting->deleteOrFail();

            return ResponseFormatter::success('Berhasil menghapus site setting');
        } catch (\Throwable $th) {
            return ResponseFormatter::error('Gagal menghapus site setting, server error', [
                'errorMsg' => $th->getMessage(),
            ]);
        }
    }

    public function history(SiteSettingDataTable $table)
    {
        return $table->render('pages.admin.setting.site-settings.histori');
    }

    public function restore(string $id)
    {
        $system_setting = SiteSetting::onlyTrashed()->find($id);
        if (! $system_setting) {
            return ResponseFormatter::error('Site setting tidak ditemukan', null, 404);
        }
        if ($system_setting->restore()) {
            return ResponseFormatter::success('Berhasil merestore site setting', code: 200);
        }

        return ResponseFormatter::error('Gagal merestore site setting, Server error', null, 500);
    }
}
