<?php

namespace App\Http\Controllers\Cms;

use App\DataTables\Cms\NewsDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\News\StoreNewsRequest;
use App\Models\Cms\News;
use App\Models\Master\KategoriBerita;
use ResponseFormatter;

class NewsController extends Controller
{
    protected $modules = ['cms', 'cms.news'];

    public function index(NewsDataTable $dataTable)
    {
        return $dataTable->render('pages.admin.cms.news.index', ['kategoris' => KategoriBerita::all()]);
    }

    public function store(StoreNewsRequest $request)
    {
        try {
            $res = News::create($request->validated());

            return ResponseFormatter::created('Berita Berhasil Ditambahkan', $res);
        } catch (\Throwable $th) {
            return ResponseFormatter::error('Gagal Menambahkan Berita, server error', [
                'trace' => $th->getMessage(),
            ], 500);
        }
    }

    public function update(StoreNewsRequest $request, News $news)
    {
        try {
            $news->updateOrFail($request->validated());

            return ResponseFormatter::success('Berita Berhasil Diubah', $news);
        } catch (\Throwable $th) {
            return ResponseFormatter::error('Gagal Mengubah Berita, server error', [
                'trace' => $th->getMessage(),
            ], 500);
        }
    }

    public function destroy(News $news)
    {
        try {
            $news->deleteOrFail();

            return ResponseFormatter::success('Berita Berhasil Dihapus');
        } catch (\Exception $e) {
            return ResponseFormatter::error('Gagal Menghapus Berita, server error', [
                'trace' => $e->getMessage(),
            ], 500);
        }
    }

    public function edit(News $news)
    {
        return Response()->json([
            ...collect($news->toArray())->except(['thumbnail']),
            'thumbnail' => getFileInfo($news->thumbnail),
        ]);
    }
}
