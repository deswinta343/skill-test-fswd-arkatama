<?php

namespace App\Http\Controllers\User;

use App\DataTables\User\UserListDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\User\StoreUserRequest;
use App\Models\User;
use App\Models\User\Role as ModelsRole;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use ResponseFormatter;

class UserController extends Controller
{
    use SendsPasswordResetEmails;

    protected $modules = ['users', 'users.user-list'];

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(UserListDataTable $dataTable)
    {
        $rolesRef = ModelsRole::all();

        return $dataTable->render('pages.admin.user.user-list.index', compact('rolesRef'));
    }

    public function store(StoreUserRequest $request)
    {
        DB::beginTransaction();

        try {
            $user = User::create([
                ...$request->only('name', 'email'),
                'password' => Hash::make($request->password),
            ]);
            $user->assignRole($request->role);
            $user->email_verified_at = now();
            $user->remember_token = Str::random(10);
            $user->save();
            DB::commit();

            return ResponseFormatter::created('Berhasil menambahkan user');
        } catch (\Throwable $th) {
            DB::rollBack();

            return ResponseFormatter::error('Gagal menambahkan user, server error', [
                'trace' => $th->getMessage(),
            ], 500);
        }
    }

    public function update(StoreUserRequest $request, User $user_list)
    {
        DB::beginTransaction();
        try {
            $updatePayload = $request->only('name', 'email');
            if ($request->password) {
                $updatePayload['password'] = Hash::make($request->password);
            }
            $user_list->updateOrFail($updatePayload);
            $user_list->syncRoles($request->role);
            DB::commit();

            return ResponseFormatter::success('Berhasil mengupdate user');
        } catch (\Throwable $th) {
            return ResponseFormatter::error('Gagal mengupdate user, server error', [
                'trace' => $th->getMessage(),
            ], 500);
        }
    }

    public function edit(User $user_list)
    {
        return response()->json([
            ...$user_list->toArray(),
            'role' => $user_list->roles[0]->name,
        ]);
    }

    public function destroy(User $user_list)
    {
        try {
            $user_list->deleteOrFail();

            return ResponseFormatter::success('Berhasil menghapus user');
        } catch (\Throwable $th) {
            return ResponseFormatter::error('Gagal menghapus user, server error', [
                'trace' => $th->getMessage(),
            ], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        $this->validateEmail($request);

        $response = $this->broker()->sendResetLink(
            $this->credentials($request)
        );
        if ($response == Password::RESET_LINK_SENT) {
            return ResponseFormatter::success('Berhasil mengirimkan email reset password');
        } else {
            return ResponseFormatter::error('Gagal mengirimkan email reset password', [
                'response' => $response,
            ], 500);
        }
    }
}
