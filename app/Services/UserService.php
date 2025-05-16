<?php

namespace App\Services;

use App\Models\User;
use App\Exceptions\UserNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserService
{
    public function getUserByEmail(string $email): User
    {
        return $this->findUserOrFail($email);
    }

    public function getUserById(int|null $userId): User
    {
        return User::findOrFail($userId);
    }

    private function findUserByEmail($email): User
    {
        return User::where('email', $email)->first()
            ?? throw new ModelNotFoundException(User::class);
    }

    public function getUserByEmailWithoutException(string $email): User|null
    {
        return User::where('email', $email)->first();
    }
}
