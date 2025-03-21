<?php

namespace App\Services;

use App\Exceptions\AdminNotFoundException;
use App\Exceptions\ArenaNotFoundException;
use App\Models\Admin;
use App\Models\Arena;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArenaService
{
    public function getAllArenas()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->isAdmin()) {
            $admin = Admin::find($user->id);

            return $admin ? $admin->arenas->with('courts') : collect();
        }

        return Arena::with(['admin', 'courts'])->get();
    }

    public function save(Request $request): Arena
    {
        $validated = $this->validateArenaData($request);
        return Arena::create($validated);
    }

    public function getArenaById(string $id): Arena
    {
        $arena = $this->findArenaOrFail($id);
        $this->authorizeArenaAccess($arena);

        return $arena;
    }

    public function getCourts(string $id)
    {
        $arena = $this->findArenaOrFail($id);
        $this->authorizeArenaAccess($arena);

        return $arena->courts()->with('timetables')->get();
    }

    public function updateArena(Request $request, string $id): Arena
    {
        $arena = $this->findArenaOrFail($id);
        $this->authorizeArenaAccess($arena);

        $data = $this->validateArenaData($request);
        $arena->update($data);;

        return $arena;
    }

    public function deleteArena(string $id): void
    {
        $arena = $this->findArenaOrFail($id);

        $this->authorizeArenaAccess($arena);

        $arena->delete();
    }

    private function validateArenaData(Request $request): array
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'street' => 'required|string|max:50',
            'number' => 'required|string|max:50',
            'neighborhood' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'state' => 'required|string|max:50',
            'zip_code' => 'required|string|max:50',
            'admin_id' => 'required',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!Admin::find($validated['admin_id'])) {
            throw new AdminNotFoundException();
        }

        if ($user->isAdmin()) {
            $validated['admin_id'] = $user->id;
        }

        return $validated;
    }

    private function findArenaOrFail(string $id): Arena
    {
        $arena = Arena::find($id);

        if (!$arena) {
            throw new ArenaNotFoundException();
        }

        return $arena;
    }

    private function authorizeArenaAccess(Arena $arena): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isAdmin() && $arena->admin_id !== $user->id) {
            throw new ArenaNotFoundException();
        }
    }
}
