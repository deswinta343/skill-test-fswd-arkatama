<?php

namespace App\Http\Controllers\Cms;

use App\DataTables\Cms\DokumenDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\Dokumen\StoreDokumenRequest;
use App\Models\Cms\Dokumen;
use Illuminate\Http\JsonResponse;
use ResponseFormatter;

class DokumenController extends Controller
{
    protected $modules = ['cms', 'cms.documents'];

    /**
     * Display a listing of the files.
     */
    public function index(DokumenDataTable $dataTable): mixed
    {
        return $dataTable->render('pages.admin.cms.dokumen.index');
    }

    /**
     * Store a file in storage.
     *
     * Dynamic validation for allowed file types and max file size from SystemSettingModel.
     * max_file_size is in KB. Example: 1024
     * allowed_file_types is a comma separated string. Example: jpg,png,pdf
     */
    public function store(StoreDokumenRequest $request): JsonResponse
    {
        $document = Dokumen::updateOrCreate(
            ['id' => $request->id],
            $request->validated()
        );

        if ($document) {
            return ResponseFormatter::success('Dokumen berhasil dibuat', $document);
        } else {
            return ResponseFormatter::error('Gagal mengunggah dokumen', code: 500);
        }

    }

    /**
     * Display the specified file.
     */
    public function edit(string $id): JsonResponse
    {
        $document = Dokumen::findOrfail($id);

        return response()->json([
            ...collect($document->toArray())->except('file'),
            'file' => getFileInfo($document->file),
        ]);
    }

    /**
     * Update the specified file in storage.
     */
    public function update(StoreDokumenRequest $request, string $id): JsonResponse
    {
        $document = Dokumen::findOrFail($id);
        if ($document->update($request->validated())) {
            return ResponseFormatter::success('Dokumen berhasil diperbarui', $document);
        } else {
            return ResponseFormatter::error('Dokumen tidak dapat diperbarui', code: 500);
        }
    }

    /**
     * Remove the specified file from storage
     *
     * @param  string  $id  ID Dokumen
     */
    public function destroy($id): JsonResponse
    {
        $document = Dokumen::find($id);
        if ($document->delete()) {
            return ResponseFormatter::success('Dokumen berhasil dihapus');
        }

        return ResponseFormatter::error('Dokumen tidak dapat dihapus', code: 500);
    }

    /**
     * Download the specified file
     *
     * @param  string  $id  ID Dokumen
     */
    public function download(string $id): mixed
    {
        $document = Dokumen::find($id);

        return $document->download();
    }

    /**
     * Restore the specified file from storage.
     *
     * @param  string  $id  ID Dokumen
     */
    public function restore(string $id): JsonResponse
    {
        $document = Dokumen::onlyTrashed()->find($id);
        if ($document->restore()) {
            return ResponseFormatter::success('Dokumen berhasil dipulihkan', $document);
        }

        return ResponseFormatter::error('Dokumen tidak dapat dipulihkan', code: 500);
    }
}
