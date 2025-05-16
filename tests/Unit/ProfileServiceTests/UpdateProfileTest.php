<?php

use App\Dtos\UpdateProfileDTO;
use App\Models\User;
use App\Services\ProfileService;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Faker\Factory as Faker;

test('example', function () {
    $faker = Faker::create();
    $updateProfileDto = new UpdateProfileDto(
        $faker->name,
        $faker->lastName,
        $faker->phoneNumber,
        $faker->email
    );

    $user = mock(User::class);
    $user->shouldReceive('update')
        ->once()->with([
            'name' => $updateProfileDto->name,
            'surname' => $updateProfileDto->surname,
            'phone' => $updateProfileDto->phone,
            'email' => $updateProfileDto->email,
        ]);
    $user->shouldReceive('getAttribute')
        ->once()->with('id')
        ->andReturn(1);

    Auth::shouldReceive('user')->andReturn($user);
    $userService = mock(UserService::class);
    $userService->shouldReceive('getUserById')
        ->once()->with(1)
        ->andReturn($user);

    $profileService = new ProfileService($userService);
    $profileService->updateProfile($updateProfileDto);
});
