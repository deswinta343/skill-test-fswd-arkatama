<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\Cms\News;
use App\Models\Master\KategoriBerita;

class NewsController extends Controller
{
    protected $randomNewsList;

    protected $categoryNewsList;

    public function __construct()
    {
        $this->randomNewsList = News::inRandomOrder()->limit(6)->get();
        $this->categoryNewsList = KategoriBerita::latest()->limit(6)->get();
    }

    public function index()
    {
        $newsList = News::latest()->paginate(6);

        return view('pages.landing.news.index', [
            'newsList' => $newsList,
            'randomNewsList' => $this->randomNewsList,
            'categoryNewsList' => $this->categoryNewsList,
        ]);
    }

    public function search()
    {
        $inputSearch = request()->input('search');
        $news = news::where('title', 'like', '%'.$inputSearch.'%')
            ->orWhere('description', 'like', '%'.$inputSearch.'%')
            ->Paginate(4);

        return view('pages.landing.news.index', [
            'newsList' => $news, 'randomNewsList' => $this->randomNewsList, 'categoryNewsList' => $this->categoryNewsList,
        ]);
    }

    public function show($id)
    {
        $detailNews = News::findOrFail($id);

        return view('pages.landing.news.detail', [
            'detailNews' => $detailNews,
            'randomNewsList' => $this->randomNewsList,
            'categoryNewsList' => $this->categoryNewsList,
        ]);
    }

    public function kategori($id)
    {
        $idKategori = KategoriBerita::findOrFail($id);
        $newsList = News::where('news_kategori_id', $idKategori->id)->paginate(7);

        return view('pages.landing.news.index', [
            'idKategori' => $idKategori, 'newsList' => $newsList, 'categoryNewsList' => $this->categoryNewsList, 'randomNewsList' => $this->randomNewsList,

        ]);
    }
}
