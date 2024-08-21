<?php

namespace App\Http\Controllers\Cms;

use App\DataTables\Cms\Slideshow\SlidesItemDataTable;
use App\Exceptions\RestrictDeleteException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\Slideshow\StoreSlideItemRequest;
use App\Models\Cms\SlideShow;
use App\Models\Cms\SlideShowItem;
use ResponseFormatter;

class SlideShowItemController extends Controller
{
    protected $modules = ['cms', 'cms.slide-show'];

    private $view = 'pages.admin.cms.slideshow.slide-item.';

    public function index(SlideShow $slideshow, SlidesItemDataTable $dataTable)
    {
        return $dataTable->render($this->view.'index', compact('slideshow'));
    }

    public function store(SlideShow $slideshow, StoreSlideItemRequest $request)
    {
        $item = $slideshow->items()->create($request->validated());

        return ResponseFormatter::created('Slide Item berhasil ditambahkan', $item);
    }

    public function update(SlideShow $slideshow, SlideShowItem $item, StoreSlideItemRequest $request)
    {
        $item->update($request->validated());

        return ResponseFormatter::success('Slide Item berhasil diperbarui');
    }

    public function destroy(SlideShow $slideshow, SlideShowItem $item)
    {
        try {
            if ($slideshow->is_active == '1') {
                return ResponseFormatter::error('Slide Show sedang aktif, tidak bisa menghapus item', code: 400);
            }

            if ($item->deleteOrFail()) {
                return ResponseFormatter::success('Slide Item berhasil dihapus');
            }
        } catch (RestrictDeleteException $e) {
            return ResponseFormatter::error($e->getMessage());
        }

        return ResponseFormatter::error('Gagal menghapus Slide Show, server error', code: 500);
    }

    public function restore(SlideShow $slideshow, $item)
    {
        $item = SlideShowItem::withTrashed()->find($item);
        $item->restore();

        return ResponseFormatter::success('Slide Item berhasil dipulihkan');
    }

    public function edit(SlideShow $slideshow, SlideShowItem $item)
    {
        return response()->json([
            ...collect($item->toArray())->except('image'),
            'image' => getFileInfo($item->image),
        ]);
    }

    /**
     * Download the specified file.
     */
    public function download($id)
    {
        $image = SlideShowItem::find($id);

        return $image->download();
    }
}
