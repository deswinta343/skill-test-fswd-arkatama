<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting\Menus;
use App\Models\User\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use ResponseFormatter;

class MenuSortingController extends Controller
{
    protected $modules = ['setting', 'settings.menu-sorting'];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $currentRole = null;
        $roleRef = Role::all();
        $currentSelectedRole = $request->get('role') ?? Auth::user()->roles[0]->name;

        if ($currentSelectedRole) {
            $currentRole = Role::where('name', $currentSelectedRole)->first();
        }
        $permissions = $currentRole ? $currentRole->permissions()->select('name')
            ->where('name', 'not like', '%-create')
            ->where('name', 'not like', '%-update')
            ->where('name', 'not like', '%-delete')
            ->where('name', 'not like', '%-read')
            ->get()->pluck('name') : collect();

        $menus = Menus::where('parent_id', null)->orderBy('order', 'asc')->where('is_active', '1')->whereIn('module', $permissions->toArray())->with(['childrens'])->oldest('created_at')->get();

        return view('pages.admin.setting.menu-sorting.index', [
            'role_ref' => $roleRef,
            'current_role' => $currentRole,
            'ref_menu' => $menus,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->only(['data']);
        try {
            DB::beginTransaction();
            foreach ($data['data'] as $key => $group) {
                foreach ($group['items'] as $i => $item) {
                    $menu = Menus::find($item);
                    $menu->order = $i;
                    $menu->parent_id = $group['parentId'];
                    $menu->save();
                }
            }
            DB::commit();

            return ResponseFormatter::success('Order menu berhasil di simpan');
        } catch (Exception  $e) {
            DB::rollBack();

            return ResponseFormatter::error('Order menu gagal di simpan', [
                'message' => $e->getMessage(),
            ]);
        }
    }
}
