<?php

namespace App\Http\Controllers\Cms;

use App\DataTables\Cms\FAQsDataTable;
use App\Exceptions\RestrictDeleteException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\FAQs\StoreFAQsRequest;
use App\Models\Cms\FAQs;
use Illuminate\Support\Facades\DB;
use ResponseFormatter;

class FAQsController extends Controller
{
    protected $modules = ['cms', 'cms.faqs'];

    public function index(FAQsDataTable $dataTable)
    {
        return $dataTable->render('pages.admin.cms.faqs.index');
    }

    public function store(StoreFAQsRequest $request)
    {
        $data = $request->validated();
        DB::beginTransaction();
        try {
            $faqs = FAQs::Create($data);
            DB::commit();

            return ResponseFormatter::created('FAQ berhasil ditambahkan', $faqs);
        } catch (\Exception $e) {
            DB::rollBack();

            return ResponseFormatter::error('Gagal menambahkan FAQ, server error', code: 500);
        }
    }

    public function edit(FAQs $faq)
    {
        return response()->json($faq);
    }

    public function update(StoreFAQsRequest $request, FAQs $faq)
    {
        try {
            $faq->updateOrFail($request->validated());

            return ResponseFormatter::success('FAQ berhasil diubah', $faq);
        } catch (\Exception $e) {
            return ResponseFormatter::error('Gagal mengubah FAQ, server error', code: 500);
        }
    }

    public function destroy($id)
    {
        $faqs = FAQs::find($id);

        try {
            if ($faqs->deleteOrFail()) {
                $faqs->update(['deleted_by' => auth()->id()]);

                return ResponseFormatter::success('FAQ berhasil dihapus');
            }
        } catch (RestrictDeleteException $e) {
            return ResponseFormatter::error($e->getMessage());
        }

        return ResponseFormatter::error('Gagal menghapus FAQ, server error', code: 500);
    }

    public function restore($id)
    {
        $faqs = FAQs::withTrashed()->find($id);
        if ($faqs->restore()) {
            return ResponseFormatter::success('FAQ berhasil direstore');
        }

        return ResponseFormatter::error('Gagal merestore FAQ, server error', code: 500);
    }
}
