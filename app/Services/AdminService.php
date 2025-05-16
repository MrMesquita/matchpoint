<?php

namespace App\Services;

use App\Exceptions\AdminNotFoundException;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\UnauthorizedException;

class AdminService
{
    private ArenaService $arenaService;

    public function __construct(
        ArenaService $arenaService
    )
    {
        $this->arenaService = $arenaService;
    }

    public function getAllAdmins(): Collection
    {
        return Admin::all();
    }

    public function createAdmin(Request $request): Admin
    {
        $data = $this->validateAdminData($request);
        return $this->storeAdmin($data);
    }

    /**
     * @throws AdminNotFoundException
     */
    public function getAdminById(string $id): Admin
    {
        return Admin::findOrFail($id);
    }

    /**
     * @throws AdminNotFoundException
     */
    public function updateAdmin(Request $request, string $id): Admin
    {
        $admin = Admin::findOrFail($id);

        $data = $this->validateAdminData($request, $admin);
        $this->updateAdminData($admin, $data);

        return $admin;
    }

    /**
     * @throws AdminNotFoundException
     */
    public function deleteAdmin(string $id): void
    {
        $admin = Admin::findOrFail($id);
        $this->deleteAdminRecord($admin);
    }

    /**
     * @throws AdminNotFoundException
     */
    public function getArenas($adminId = null)
    {
        $admin = Admin::findOrFail($adminId ?? Auth::id());
        return $admin->arenas->all();
    }

    private function validateAdminData(Request $request, Admin $admin = null): array
    {
        $uniquePhoneRule = $admin
            ? Rule::unique('users')->ignore($admin->id)
            : 'unique:users';

        $uniqueEmailRule = $admin
            ? Rule::unique('users')->ignore($admin->id)
            : 'unique:users';

        return $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'phone' => ['required', $uniquePhoneRule],
            'email' => ['required', 'email', $uniqueEmailRule],
            'password' => 'required|string|min:6'
        ]);
    }

    private function storeAdmin(array $data): Admin
    {
        $data['password'] = Hash::make($data['password']);
        return Admin::create($data);
    }

    private function updateAdminData(Admin $admin, array $data): void
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $admin->update($data);
    }

    private function deleteAdminRecord(Admin $admin): void
    {
        $admin->delete();
    }

    public function createArena(Request $request)
    {
        $arena = $this->arenaService->save($request);
        return $arena;
    }
}
