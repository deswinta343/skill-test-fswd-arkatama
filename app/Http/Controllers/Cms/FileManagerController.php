<?php

namespace App\Http\Controllers\Cms;

use App\DataTables\Cms\FileManagerDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\FileManagement\StoreFileManagementRequest;
use App\Models\Cms\FileManagement;
use App\Models\User;
use ResponseFormatter;

class FileManagerController extends Controller
{
    protected $modules = ['cms', 'cms.file-manager'];

    /**
     * Display a listing of the files.
     */
    public function index(FileManagerDataTable $dataTable)
    {
        $users = User::all();

        return $dataTable->render('pages.admin.cms.file-manager.index', [
            'users' => $users,
        ]);
    }

    /**
     * Store a file in storage.
     *
     * Dynamic validation for allowed file types and max file size from SystemSettingModel.
     * max_file_size is in KB. Example: 1024
     * allowed_file_types is a comma separated string. Example: jpg,png,pdf
     *
     * @param  \App\Http\Requests\FileManagement\StoreFileManagementRequest  $request
     */
    public function store(StoreFileManagementRequest $request)
    {
        try {
            $fileManagement = FileManagement::create($request->validated());

            return ResponseFormatter::created('Berhasil menambahkan file', $fileManagement);
        } catch (\Throwable $th) {
            return ResponseFormatter::error('Gagal menambahkan file, server error', [
                'trace' => $th->getMessage(),
            ], code: 500);
        }
    }

    /**
     * Display the specified file.
     */
    public function edit(FileManagement $file_manager)
    {
        return response()->json([
            ...collect($file_manager->toArray())->except(['file', 'user_id']),
            'file' => getFileInfo($file_manager->file),
            'user_id' => [
                'value' => $file_manager->user_id,
                'label' => $file_manager->user->name,
            ],
        ]);
    }

    public function update(StoreFileManagementRequest $request, FileManagement $file_manager)
    {
        try {
            $file_manager->updateOrFail($request->validated());

            return ResponseFormatter::success('Berhasil mengupdate file', $file_manager);
        } catch (\Throwable $th) {
            return ResponseFormatter::error('Gagal mengupdate file, server error', [
                'trace' => $th->getMessage(),
            ], code: 500);
        }
    }

    /**
     * Remove the specified file from storage.
     */
    public function destroy(FileManagement $file_manager)
    {
        try {
            $file_manager->deleteOrFail();

            return ResponseFormatter::success('Berhasil menghapus file');
        } catch (\Throwable $th) {
            return ResponseFormatter::error('Gagal menghapus file, server error', [
                'trace' => $th->getMessage(),
            ], code: 500);
        }
    }

    /**
     * Download the specified file.
     */
    public function download($id)
    {
        $fileManagement = FileManagement::find($id);

        return $fileManagement->download();
    }
}
