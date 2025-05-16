<?php

namespace App\Services;

use App\Dtos\UpdateProfileDTO;
use App\Http\Resources\ProfileResource;
use App\Models\User;

class ProfileService
{
    private UserService $userService;
    private User $user;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $userId = auth()->user()?->id;
        $this->user = $this->userService->getUserById($userId);
    }

    public function getProfileData(): ProfileResource
    {
        return ProfileResource::make($this->user);
    }

    public function updateProfile(UpdateProfileDTO $updateProfileDTO): void
    {
        $this->user->update([
            'name' => $updateProfileDTO->name,
            'surname' => $updateProfileDTO->surname,
            'phone' => $updateProfileDTO->phone,
            'email' => $updateProfileDTO->email,
        ]);
    }
}
