<?php

namespace App\Http\Controllers\Setting;

use App\DataTables\Setting\SystemSettingDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\SystemSetting\StoreSettingRequest;
use App\Models\Setting\SystemSettingModel;
use ResponseFormatter;

class SystemSettingController extends Controller
{
    protected $modules = ['setting', 'settings.system-setting'];

    public function index(SystemSettingDataTable $dataTable)
    {
        return $dataTable->render('pages.admin.setting.system-setting.index');
    }

    public function store(StoreSettingRequest $request)
    {
        try {
            $res = SystemSettingModel::create(array_merge(
                $request->validated(),
                ['type' => gettype($request->value)]
            ));

            return ResponseFormatter::created('Berhasil menambahkan system setting', $res);
        } catch (\Exception $e) {
            return ResponseFormatter::error('Gagal menambahkan system setting, server error', $e->getMessage(), $e->getCode());
        }
    }

    public function edit(SystemSettingModel $systemSetting)
    {
        return response()->json($systemSetting);
    }

    public function update(StoreSettingRequest $request, SystemSettingModel $systemSetting)
    {
        try {
            $systemSetting->updateOrFail(array_merge(
                $request->validated(),
                ['type' => gettype($request->value)]
            ));

            return ResponseFormatter::success('Berhasil mengupdate system setting', $systemSetting);
        } catch (\Exception $e) {
            return ResponseFormatter::error('Gagal mengupdate system setting, server error', $e->getMessage(), $e->getCode());
        }
    }

    public function destroy(SystemSettingModel $systemSetting)
    {
        try {
            $systemSetting->deleteOrFail();

            return ResponseFormatter::success('Berhasil menghapus system setting');
        } catch (\Exception $e) {
            return ResponseFormatter::error('Gagal menghapus system setting, server error', $e->getMessage(), $e->getCode());
        }
    }

    public function history(SystemSettingDataTable $table)
    {
        return $table->render('pages.admin.setting.system-setting.history');
    }

    public function restore(string $id)
    {
        $system_setting = SystemSettingModel::onlyTrashed()->find($id);
        if (! $system_setting) {
            return ResponseFormatter::error('System setting tidak ditemukan', null, 404);
        }
        if ($system_setting->restore()) {
            return ResponseFormatter::success('Berhasil merestore system setting', code: 200);
        }

        return ResponseFormatter::error('Gagal merestore system setting, Server error', null, 500);
    }
}
