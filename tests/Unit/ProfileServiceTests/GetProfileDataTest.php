<?php

use App\Models\User;
use App\Services\ProfileService;
use App\Services\UserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Testing\Fakes;
use Faker\Factory as Faker;

test('it can be get profile data', function () {
    $faker = Faker::create();

    $mockedData = [
        'id' => 1,
        'name' => $faker->name,
        'surname' => $faker->lastName,
        'phone' => $faker->phoneNumber,
        'email' => $faker->email,
    ];

    $user = mock(\App\Models\User::class);
    $user->shouldReceive('getAttribute')
        ->andReturnUsing(fn($key) => $mockedData[$key] ?? null);

    Auth::shouldReceive('user')->andReturn($user);

    $userService = mock(UserService::class);
    $userService->shouldReceive('getUserById')->once()->andReturn($user);

    $profileService = new ProfileService($userService);
    $resource = $profileService->getProfileData();

    expect($resource->toArray(request()))->toMatchArray($mockedData);
});

test('try get profile data without login', function () {
    $userService = mock(UserService::class);
    $userService->shouldReceive('getUserById')
        ->once()->andThrow(ModelNotFoundException::class);

    $profileService = new ProfileService($userService);

    $profileService->getProfileData();
})->throws(ModelNotFoundException::class);
