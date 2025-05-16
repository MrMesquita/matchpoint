<?php

namespace App\Services;

use App\Exceptions\CourtNotFoundException;
use App\Models\Admin;
use App\Models\Court;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CourtService
{
    private ArenaService $arenaService;
    private AdminService $adminService;

    public function __construct(
        ArenaService $arenaService,
        AdminService $adminService
    )
    {
        $this->adminService = $adminService;
        $this->arenaService = $arenaService;
    }

    public function getAllCourts()
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user->isAdmin()) {
            return $this->getAdminCourts($user);
        }

        return Court::all();
    }

    public function save(Request $request): Court
    {
        $validated = $this->validateCourtData($request);
        return Court::create($validated);
    }

    public function getCourtById(string $id): Court
    {
        $court = Court::findOrFail($id);
        $this->authorizeCourtAccess($court);
        return $court;
    }

    public function updateCourt(Request $request, string $id): Court
    {
        $court = Court::findOrFail($id);
        $this->authorizeCourtAccess($court);
        $data = $this->validateCourtData($request);

        $court->update($data);
        return $court;
    }

    public function deleteCourt(string $id): void
    {
        $court = Court::findOrFail($id);
        $this->authorizeCourtAccess($court);
        $court->delete();
    }

    protected function validateCourtData(Request $request): array
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('courts')->where('arena_id', $request->input('arena_id')),
            ],
            'capacity' => 'required|integer|min:1',
            'arena_id' => 'required'
        ]);

        /** @var User $user */
        $user = Auth::user();
        $arenaSent = $this->arenaService->getArenaById($validated['arena_id']);

        if ($user->isAdmin()) {
            $adminArenas = $this->adminService->getArenas();

            if (!in_array($arenaSent, $adminArenas)) {
                $validated['arena_id'] = $adminArenas[0];
            }
        }

        return $validated;
    }

    protected function getAdminCourts($user)
    {
        $admin = Admin::find($user->id);
        return $admin ? $admin->arenas()->with('courts')->get()->pluck('courts')->flatten() : collect();
    }

    protected function authorizeCourtAccess(Court $court): void
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->isAdmin() && $court->arena->admin_id !== $user->id) {
            throw new ModelNotFoundException(Court::class);
        }
    }
}
