<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting\Menus;
use Illuminate\Support\Facades\File;

class AutoRouteController extends Controller
{
    public function rewriteRouteFile($id = 1)
    {
        $filePath = base_path('routes/web.php');
        $fileContent = File::get($filePath);

        $newContent = $fileContent."\n // Your new content here";

        File::put($filePath, $newContent);

        $this->prefillContent($id);

        return 'Route has ben rewrite';
    }

    public function prefillContent($id)
    {
        $menu = Menus::find($id);

        $filePath = base_path('routes/web.php');
        $fileContent = File::get($filePath);

        $newContent = $fileContent."\n // Your new content here\n";

        $view = str_replace('/', '.', substr($menu->url, 1));
        $newContent .= "Route::get('".$menu->url."',function () {return view('pages.".$view.".index'); });";

        File::put($filePath, $newContent);

        $directoryPath = base_path('resources/views/pages/'.$menu->url);
        File::makeDirectory($directoryPath, 0755, true, true);

        $viewPath = $directoryPath.'/index.blade.php';
        $content = <<<HTML
                @extends('layouts.app')

                @section('content')
                    <div id="kt_app_content" class="app-content flex-column-fluid">
                        <div class="app-toolbar py-3 py-lg-6">
                            <div class="app-container container-xxl d-flex flex-stack">
                                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">

                                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                                        $menu->name
                                    </h1>

                                </div>
                            </div>
                        </div>

                        <div class="app-container container-xxl">
                            <h1>This is $menu->name page</h1>
                        </div>
                    </div>
                @endsection
                HTML;

        File::put($viewPath, $content);
    }

    public function updateRouteFile($id, $newMenu)
    {
        $oldMenu = Menus::find($id);

        if (! $newMenu) {
            return redirect()->back()->with('error', 'Menu not found.');
        }

        $filePath = base_path('routes/web.php');
        $fileContent = File::get($filePath);
        $oldRoute = "Route::get('".$oldMenu->url."',function () {return view('pages.".str_replace('/', '.', substr($oldMenu->url, 1)).".index'); });";

        $newRoute = "Route::get('".$newMenu['url']."',function () {return view('pages.".str_replace('/', '.', substr($newMenu['url'], 1)).".index'); });";

        $newContent = str_replace($oldRoute, $newRoute, $fileContent);

        chmod($filePath, 0777);
        File::put($filePath, $newContent);

        $directoryPath = base_path('resources/views/pages'.$oldMenu->url);
        $newDirectoryPath = base_path('resources/views/pages'.$newMenu['url']);

        File::move($directoryPath, $newDirectoryPath);

        File::deleteDirectory($directoryPath);

        return redirect()->route('menus.index')->with('success', 'Menu berhasil diubah.');
    }

    public function deleteRouteFile($id)
    {
        $menu = Menus::find($id);

        if (! $menu) {
            return redirect()->back()->with('error', 'Menu not found.');
        }

        $filePath = base_path('routes/web.php');
        $fileContent = File::get($filePath);

        $routeContent = "Route::get('".$menu->url."',function () {return view('pages.".str_replace('/', '.', substr($menu->url, 1)).".index'); });";
        $newContent = str_replace($routeContent, '', $fileContent);

        File::put($filePath, $newContent);

        chmod($filePath, 0777);

        $directoryPath = base_path('resources/views/pages/'.$menu->url);

        File::deleteDirectory($directoryPath);

        return redirect()->route('menus.index')->with('success', 'Menu berhasil hapus.');
    }
}
