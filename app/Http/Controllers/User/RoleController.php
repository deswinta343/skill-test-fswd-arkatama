<?php

namespace App\Http\Controllers\User;

use App\DataTables\User\RoleDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\Role\StoreRoleRequest;
use App\Models\Setting\Access;
use App\Models\Setting\Menus;
use App\Models\User\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use ResponseFormatter;
use Spatie\Permission\Models\Role as PermissionModelsRole;

class RoleController extends Controller
{
    protected $modules = ['users', 'users.role'];

    public function index(RoleDataTable $dataTable)
    {
        return $dataTable->render('pages.admin.user.role.index');
    }

    public function store(StoreRoleRequest $request)
    {
        try {
            $res = Role::create($request->validated());

            return ResponseFormatter::created('Berhasil Menambahkan Role', $res);
        } catch (\Throwable $th) {
            return ResponseFormatter::error('Gagal Menambahkan Role, server error', [
                'trace' => $th->getMessage(),
            ], code: 500);
        }
    }

    public function show(Role $role)
    {
        $menuRef = Menus::all();
        $roleName = PermissionModelsRole::findByName($role->name);
        $permissions = PermissionModelsRole::findByName($role->name)->permissions->pluck('name')->toArray();
        $access = Access::all();

        return view('pages.admin.user.role.show', compact('menuRef', 'permissions', 'access', 'role'));
    }

    public function edit(Role $role)
    {
        return response()->json($role);
    }

    public function update(StoreRoleRequest $request, Role $role)
    {
        try {
            $role->update($request->validated());

            return ResponseFormatter::success('Berhasil mengupdate Role');
        } catch (\Throwable $th) {
            return ResponseFormatter::error('Gagal mengupdate Role, server error', [
                'trace' => $th->getMessage(),
            ], code: 500);
        }
    }

    public function destroy(Role $role)
    {
        try {
            $role->deleteOrFail();

            return ResponseFormatter::success('Berhasil menghapus role');
        } catch (\Throwable $th) {
            return ResponseFormatter::error('Gagal menghapus role, server error', [
                'trace' => $th->getMessage(),
            ], code: 500);
        }
    }

    public function updatePermissions(Request $request, Role $role)
    {
        DB::beginTransaction();
        try {
            $rolePermission = PermissionModelsRole::findByName($role->name);
            $allAccess = $request->except(['_token', '_method']);
            $res = [];
            foreach ($allAccess as $key => $val) {
                $res[str_replace('_', '.', $key)] = $val;
            }
            $rolePermission->syncPermissions(array_keys(array_filter($res)));
            DB::commit();

            return ResponseFormatter::success('Berhasil mengupdate akses role', code: 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return ResponseFormatter::error('Gagal mengupdate permission, server error', [
                'trace' => $th->getMessage(),
            ], code: 500);
        }
    }

    public function exportJson()
    {
        $permissions = Role::with(['permissions' => function ($query) {
            $query->select('name');
        }])->get()->makeHidden('id');

        $permissions = $permissions->map(function ($item) {
            return [
                'role' => $item->name,
                'permissions' => $item->permissions->pluck('name'),
            ];
        });

        $jsonData = json_encode($permissions, JSON_PRETTY_PRINT);
        $fileName = 'access.json';

        return response()->streamDownload(function () use ($jsonData) {
            echo $jsonData;
        }, $fileName, ['Content-Type' => 'application/json']);
    }
}
