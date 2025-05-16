<?php

namespace App\Http\Controllers;

use App\Dtos\UpdateProfileDTO;
use App\Http\Requests\UpdateProfileRequest;
use App\Services\ProfileService;
use Illuminate\Http\Request;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\MediaType;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Put;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

#[OA\Tag(name: "Profiles")]
class ProfileController
{
    private ProfileService $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    #[Get(
        path: "/api/v1/profiles/",
        description: "Get a profile",
        summary: "Get a profile",
        tags: ["profiles"],
        security: [['bearerAuth' => []]],
        responses: [
            new Response(
                response: 200,
                description: "List returned successfully",
                content: new JsonContent(
                    properties: [
                        new Property(property: "success", type: "boolean", example: true),
                        new Property(
                            property: "results",
                            type: "array",
                            items: new Items(ref: "#/components/schemas/ProfileResponse")
                        )
                    ],
                    type: "object"
                )
            ),
            new OA\Response(
                response: 401,
                description: "Tries access without system login",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: "success", type: "boolean", example: false),
                            new OA\Property(property: "message", type: "string", example: "Unauthenticated.")
                        ]
                    )
                )
            ),
            new OA\Response(
                response: 404,
                description: "Admin not found",
                content: new OA\JsonContent(ref: "#/components/schemas/NotFound")
            )
        ]
    )]
    public function profile()
    {
        $data = $this->profileService->getProfileData();
        return success_response($data);
    }

    #[Put(
        path: "/api/v1/profiles",
        description: "Edit a profile",
        summary: "Edit a profile",
        tags: ["profiles"],
        security: [['bearerAuth' => []]],
        responses: [
            new Response(
                response: 200,
                description: "Profile updated successfully",
                content: new JsonContent(
                    properties: [
                        new Property(property: "success", type: "boolean", example: true),
                        new Property(property: "message", type: "string", example: "Update profile.")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Tries access without system login",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: "success", type: "boolean", example: false),
                            new OA\Property(property: "message", type: "string", example: "Unauthenticated.")
                        ]
                    )
                )
            ),
            new OA\Response(
                response: 404,
                description: "User not found",
                content: new OA\JsonContent(ref: "#/components/schemas/NotFound")
            )
        ]
    )]
    public function updateProfile(UpdateProfileRequest $request)
    {
        $this->profileService->updateProfile($request->toDTO());
        return success_response(null, "Profile updated successfully");
    }
}
