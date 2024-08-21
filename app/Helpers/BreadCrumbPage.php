<?php

use Illuminate\Support\Facades\Route;

class BreadCrumbPage
{
    public static function render($firstBreadcrumb = '')
    {
        // Mendapatkan URL saat ini
        $currentUrl = Route::current()->uri();
        $routeParams = Route::current()->parameters();

        foreach (Route::getRoutes() as $route) {
            if ($route->methods[0] == 'GET') {
                $routeUrls[] = $route->uri();
            }
        }

        // Menghapus protokol HTTP dan alamat IP

        $existRoutes = [];

        // Explode currentUrl berdasarkan '/'
        $urlSegments = array_filter(explode('/', $currentUrl), function ($val) {
            return $val !== '';
        });

        foreach ($urlSegments as $i => $segment) {
            // Construct a string that contains the current segment and the previous segment
            $prevSegment = implode('/', array_slice($urlSegments, 0, $i));
            if ($prevSegment == '') {
                $segmentWithPrev = $segment;
            } else {
                $segmentWithPrev = $prevSegment.'/'.$segment;
            }
            $i = array_search($segmentWithPrev, $routeUrls);
            if ($i !== false) {
                $existRoutes[] = $segmentWithPrev;
            }
        }

        // dd($existRoutes);

        $route = 'asesor/penilaian-portofolio/pratinjau/{mk}/nilai/{formulir}';
        $breadcrumb = [];
        foreach ($existRoutes as $route) {
            $breadcrumb[] = [
                'url' => self::generateURl($route, $routeParams),
                'label' => self::generateLabel($route, $routeParams),
            ];
        }

        return $breadcrumb;
    }

    private static function generateURl($route, $routeParams)
    {
        $currentUrl = Route::current()->uri();

        if (str_ends_with($route, '}') && $route != $currentUrl) {
            return null;
        }

        return preg_replace_callback("/\{([^}]*)\}/", function ($matches) use ($routeParams) {

            $param = $routeParams[$matches[1]];
            $primary = $param->bcPrimaryKey ?? 'id';

            return $param->$primary;
        }, $route);
    }

    private static function generateLabel($route, $routeParams)
    {
        $lastUrl = explode('/', $route);
        $baseLabel = end($lastUrl);
        $res = preg_replace_callback("/\{.*\}/", function ($matches) use ($routeParams) {
            $key = str_replace(['{', '}'], '', $matches[0]);
            $param = $routeParams[$key];
            $breadcrumbCol = $param->breadcrumbLabelCol ?? 'name';

            return $param->$breadcrumbCol;
        }, $baseLabel);

        return ucwords(str_replace(['-', '_'], ' ', $res));
    }
}
