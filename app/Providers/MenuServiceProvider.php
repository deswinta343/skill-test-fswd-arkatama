<?php

namespace App\Providers;

use App\Models\Setting\Menus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (Schema::hasTable('menus')) {
            $menus = Menus::with(['childrens' => function ($q) {
                $q->where('is_active', '1')->orderBy('order', 'asc');
            }])->where('parent_id', null)->where('is_active', '1')->orderBy('order', 'asc')->get();
            view()->share('menus', $menus);
        }
    }
}
