<?php

namespace App\Http\Controllers;

use App\Services\AdminService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminController extends BaseController
{
    public function __construct(
        private AdminService $adminService
    ) {}

    /**
     * @OA\Get(
     *     path="/api/admins",
     *     summary="List all administrators",
     *     tags={"Administrators"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List return successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="results",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=2),
     *                     @OA\Property(property="name", type="string", example="Admin"),
     *                     @OA\Property(property="surname", type="string", example="test"),
     *                     @OA\Property(property="phone", type="string", example="1234568900"),
     *                     @OA\Property(property="email", type="string", example="admin@admin.com"),
     *                     @OA\Property(property="email_verified_at", type="string", nullable=true, example=null),
     *                     @OA\Property(property="type", type="string", example="admin"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-02T18:46:22.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-02T18:46:22.000000Z"),
     *                     @OA\Property(property="deleted_at", type="string", nullable=true, example=null)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Tries access without system login",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="success", type="boolean", example=false),
     *                 @OA\Property(property="message", type="string", example="Unauthenticated.")
     *             )
     *         )
     *     )
     * )
     * @return JsonResponse
     */
    public function index()
    {
        return success_response($this->adminService->getAllAdmins());
    }

    /**
     * @OA\Post(
     *     path="/api/admins",
     *     summary="Create a new admin",
     *     tags={"Administrators"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "surname", "phone", "email", "password"},
     *             @OA\Property(property="name", type="string", example="New"),
     *             @OA\Property(property="surname", type="string", example="Admin"),
     *             @OA\Property(property="phone", type="string", example="12345678900"),
     *             @OA\Property(property="email", type="string", example="newadmin@example.com"),
     *             @OA\Property(property="password", type="string", example="123456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Admin created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="results",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=7),
     *                     @OA\Property(property="type", type="string", example="admin"),
     *                     @OA\Property(property="name", type="string", example="Marcelo"),
     *                     @OA\Property(property="surname", type="string", example="Mesquita"),
     *                     @OA\Property(property="phone", type="string", example="7677"),
     *                     @OA\Property(property="email", type="string", example="767788@admin.com"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-02-04T23:28:32.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-04T23:28:32.000000Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="The phone has already been taken. (and 1 more error)"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="phone",
     *                     type="array",
     *                     @OA\Items(type="string", example="The phone has already been taken.")
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="array",
     *                     @OA\Items(type="string", example="The email field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Tries access without system login",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="success", type="boolean", example=false),
     *                 @OA\Property(property="message", type="string", example="Unauthenticated.")
     *             )
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $admin = $this->adminService->createAdmin($request);
        return success_response($admin, null, Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/admins/{id}",
     *     summary="Get an admin by id",
     *     tags={"Administrators"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Admin ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Admin founded successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="results",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=7),
     *                     @OA\Property(property="type", type="string", example="admin"),
     *                     @OA\Property(property="name", type="string", example="Marcelo"),
     *                     @OA\Property(property="surname", type="string", example="Mesquita"),
     *                     @OA\Property(property="phone", type="string", example="7677"),
     *                     @OA\Property(property="email", type="string", example="767788@admin.com"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-02-04T23:28:32.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-04T23:28:32.000000Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Admin not found",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="success", type="boolean", example=false),
     *                 @OA\Property(property="message", type="string", example="Administrator not found.")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Tries access without system login",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="success", type="boolean", example=false),
     *                 @OA\Property(property="message", type="string", example="Unauthenticated.")
     *             )
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        $admin = $this->adminService->getAdminById($id);
        return success_response($admin);
    }

    /**
     * @OA\Put(
     *     path="/api/admins/{id}",
     *     summary="Update admin data by id",
     *     tags={"Administrators"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Admin ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            @OA\Property(property="name", type="string", example="New"),
     *            @OA\Property(property="surname", type="string", example="Admin"),
     *            @OA\Property(property="phone", type="string", example="12345678900"),
     *            @OA\Property(property="email", type="string", example="newadmin@example.com")
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Admin successfully updated",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="results",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=2),
     *                     @OA\Property(property="name", type="string", example="34343"),
     *                     @OA\Property(property="surname", type="string", example="Mesquita"),
     *                     @OA\Property(property="phone", type="string", example="353535"),
     *                     @OA\Property(property="email", type="string", example="admin@admin.com"),
     *                     @OA\Property(property="email_verified_at", type="string", nullable=true, example=null),
     *                     @OA\Property(property="type", type="string", example="admin"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-02T18:46:22.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-05T00:29:28.000000Z"),
     *                     @OA\Property(property="deleted_at", type="string", nullable=true, example=null)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Tries access without system login",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="success", type="boolean", example=false),
     *                 @OA\Property(property="message", type="string", example="Unauthenticated.")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="The phone has already been taken. (and 1 more error)"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="phone",
     *                     type="array",
     *                     @OA\Items(type="string", example="The phone has already been taken.")
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="array",
     *                     @OA\Items(type="string", example="The email field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Admin not found",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="success", type="boolean", example=false),
     *                 @OA\Property(property="message", type="string", example="Administrator not found.")
     *             )
     *         )
     *     )
     * )
     */
    public function update(Request $request, string $id)
    {
        $admin = $this->adminService->updateAdmin($request, $id);
        return success_response($admin);
    }

    /**
     * @OA\Delete(
     *     path="/api/admins/{id}",
     *     summary="Remove an admin by ID",
     *     tags={"Administrators"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Admin ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Destroy an admin successfully"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $this->adminService->deleteAdmin($id);
        return success_response(null, null, Response::HTTP_NO_CONTENT);
    }
}
