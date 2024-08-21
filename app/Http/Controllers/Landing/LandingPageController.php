<?php

namespace App\Http\Controllers\Landing;

use App\DataTables\Landing\UnduhDataTable;
use App\Http\Controllers\Controller;
use App\Models\Akademik\TahunAjaran;
use App\Models\Cms\Dokumen;
use App\Models\Cms\FAQs;
use App\Models\Cms\SlideShow;
use App\Models\Cms\SlideShowItem;
use App\Models\Cms\Timeline;
use App\Models\Master\JenjangPendidikan;
use App\Models\Payment\PaymentProdi;
use App\Models\Rpl\Formulir;
use App\Models\Rpl\ProdiPilihan;
use Carbon\Carbon;

class LandingPageController extends Controller
{
    public function index()
    {
        $faqs = FAQs::where('is_active', '1')->get();

        $slideshows = SlideShow::where('is_active', '1')->get();
        $slideitems = SlideShowItem::whereIn('slideshow_id', $slideshows->pluck('id'))->get()->toArray();


        return view('pages.landing.index', compact('faqs', 'slideshows', 'slideitems'));
    }

    public function unduh(UnduhDataTable $dataTable)
    {
        return $dataTable->render('pages.landing.unduh.index');
    }

    public function download($id)
    {
        $document = Dokumen::find($id);

        return $document->download();
    }
}
