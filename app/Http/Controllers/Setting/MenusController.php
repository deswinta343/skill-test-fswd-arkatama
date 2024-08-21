<?php

namespace App\Http\Controllers\Setting;

use App\DataTables\Setting\MenusDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Menu\StoreMenuRequest;
use App\Models\Setting\Access;
use App\Models\Setting\Menus;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use ResponseFormatter;
use Spatie\Permission\Models\Permission;

class MenusController extends Controller
{
    protected $modules = ['settings.menu'];

    protected $actions = [];

    public function index(MenusDataTable $dataTable)
    {
        return $dataTable->render('pages.admin.setting.menus.index');
    }

    public function create()
    {
        $this->middleware = ['permission:settings.menu-create'];
        $this->modules = array_merge($this->modules, ['settings.menu-create']);
        $menuRef = Menus::all();

        return view('pages.admin.setting.menus.create', compact('menuRef'));
    }

    public function store(StoreMenuRequest $request)
    {
        DB::beginTransaction();
        try {
            $newMenu = Menus::create($request->validated());
            $access = ['create', 'update', 'delete', 'read'];

            $permissions[] = [
                'name' => $newMenu->module,
                'guard_name' => 'web',
            ];
            foreach ($access as $acc) {
                $permissions[] = [
                    'name' => "$newMenu->module-$acc",
                    'guard_name' => 'web',
                ];
                $accesses[] = [
                    'name' => "$newMenu->name $acc",
                    'module' => "$newMenu->module-$acc",
                    'menus_id' => $newMenu->id,
                ];
            }
            Access::insert($accesses);
            Permission::insert($permissions);

            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');

            DB::commit();

            return ResponseFormatter::created('Berhasil membuat menu', $newMenu);
        } catch (\Exception $e) {
            DB::rollBack();

            return ResponseFormatter::error('Gagal membuat menu, server error', [
                'message' => $e->getMessage(),
            ], code: 500);
        }
    }

    public function destroy(Menus $menu)
    {
        DB::beginTransaction();
        try {
            $menu->deleteOrFail();
            Permission::whereIn('name', [
                $menu->module,
                $menu->module.'-read',
                $menu->module.'-create',
                $menu->module.'-update',
                $menu->module.'-delete',
            ])->delete();
            DB::commit();
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');

            return ResponseFormatter::success('Berhasil menghapus menu');
        } catch (\Exception $e) {
            DB::rollBack();

            return ResponseFormatter::error('Gagal menghapus menu, server error', [
                'message' => $e->getMessage(),
            ], code: 500);
        }
    }

    public function edit(Menus $menu)
    {
        $parents = Menus::where('id', '!=', $menu->id)->get();
        $menu->loadMissing('parent');

        return view('pages.admin.setting.menus.edit', compact('menu', 'parents'));
    }

    public function update(StoreMenuRequest $request, Menus $menu)
    {

        try {
            $payload = $request->validated();
            $menu->updateOrFail($payload);
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');

            return ResponseFormatter::success('Berhasil mengupdate menu', $menu);
        } catch (\Exception $e) {
            return ResponseFormatter::error('Gagal mengupdate menu, server error', [
                'message' => $e->getMessage(),
            ], code: 500);
        }

        return redirect()->route('menus.index');
    }

    public function exportJson()
    {
        $menus = Menus::where('parent_id', null)->where('is_active', '1')->orderBy('order', 'asc')->get()->makeHidden('id');
        $menus->each(function ($menu) {
            $this->hideIdRecursively($menu);
        });
        $jsonData = json_encode($menus->toArray(), JSON_PRETTY_PRINT);
        $fileName = 'menus.json';

        return response()->streamDownload(function () use ($jsonData) {
            echo $jsonData;
        }, $fileName, ['Content-Type' => 'application/json']);
    }

    private function hideIdRecursively($menu)
    {
        $menu->childrens->each(function ($child) {
            $this->hideIdRecursively($child);
        });
        $menu->makeHidden('id', 'target', 'parent_id', 'type', 'is_active', 'created_at', 'updated_at', 'deleted_at', 'location');
    }
}
