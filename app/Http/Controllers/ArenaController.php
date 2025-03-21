<?php

namespace App\Http\Controllers;

use App\Services\ArenaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ArenaController
{
    public function __construct(
        private ArenaService $arenaService
    ) {
    }

    /**
     * @OA\Get(
     *     path="/api/arenas",
     *     summary="List all arenas",
     *     tags={"Arenas"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List returned successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="results",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=2),
     *                     @OA\Property(property="admin_id", type="integer", example=2),
     *                     @OA\Property(property="name", type="string", example="Arena 1"),
     *                     @OA\Property(property="street", type="string", example="Rua lagoa"),
     *                     @OA\Property(property="number", type="integer", example=44),
     *                     @OA\Property(property="neighborhood", type="string", example="Bairro"),
     *                     @OA\Property(property="city", type="string", example="Vitoria"),
     *                     @OA\Property(property="state", type="string", example="PE"),
     *                     @OA\Property(property="zip_code", type="string", example="5569955"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-02T18:46:22.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-02T18:46:22.000000Z"),
     *                     @OA\Property(property="deleted_at", type="string", nullable=true, example=null),
     *
     *                     @OA\Property(
     *                         property="admin",
     *                         type="object",
     *                         nullable=true,
     *                         @OA\Property(property="id", type="integer", example=2),
     *                         @OA\Property(property="name", type="string", example="34343"),
     *                         @OA\Property(property="surname", type="string", example="Mesquita"),
     *                         @OA\Property(property="phone", type="string", example="353535"),
     *                         @OA\Property(property="email", type="string", example="admin@admin.com"),
     *                         @OA\Property(property="email_verified_at", type="string", nullable=true, example=null),
     *                         @OA\Property(property="type", type="string", example="admin"),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-02T18:46:22.000000Z"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-07T17:20:21.000000Z"),
     *                         @OA\Property(property="deleted_at", type="string", nullable=true, example=null)
     *                     ),
     *
     *                     @OA\Property(
     *                         property="courts",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="name", type="string", example="Quadra 3"),
     *                             @OA\Property(property="capacity", type="integer", example=10),
     *                             @OA\Property(property="arena_id", type="integer", example=1),
     *                             @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-02T18:46:45.000000Z"),
     *                             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-02T18:46:45.000000Z"),
     *                             @OA\Property(property="deleted_at", type="string", nullable=true, example=null)
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Tries to access without system login",
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
    public function index(): JsonResponse
    {
        return success_response($this->arenaService->getAllArenas());
    }

    public function store(Request $request): JsonResponse
    {
        $arena = $this->arenaService->save($request);
        return success_response($arena, "Arena created successfully", Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/arenas/{id}",
     *     summary="find arena by id",
     *     tags={"Arenas"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of the arena",
     *          required=true,
     *          @OA\Schema(type="string", example="1")
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Returned successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="results",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=2),
     *                     @OA\Property(property="admin_id", type="integer", example=2),
     *                     @OA\Property(property="name", type="string", example="Arena 1"),
     *                     @OA\Property(property="street", type="string", example="Rua lagoa"),
     *                     @OA\Property(property="number", type="integer", example=44),
     *                     @OA\Property(property="neighborhood", type="string", example="Bairro"),
     *                     @OA\Property(property="city", type="string", example="Vitoria"),
     *                     @OA\Property(property="state", type="string", example="PE"),
     *                     @OA\Property(property="zip_code", type="string", example="5569955"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-02T18:46:22.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-02T18:46:22.000000Z"),
     *                     @OA\Property(property="deleted_at", type="string", nullable=true, example=null),
     *
     *                     @OA\Property(
     *                         property="admin",
     *                         type="object",
     *                         nullable=true,
     *                         @OA\Property(property="id", type="integer", example=2),
     *                         @OA\Property(property="name", type="string", example="34343"),
     *                         @OA\Property(property="surname", type="string", example="Mesquita"),
     *                         @OA\Property(property="phone", type="string", example="353535"),
     *                         @OA\Property(property="email", type="string", example="admin@admin.com"),
     *                         @OA\Property(property="email_verified_at", type="string", nullable=true, example=null),
     *                         @OA\Property(property="type", type="string", example="admin"),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-02T18:46:22.000000Z"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-07T17:20:21.000000Z"),
     *                         @OA\Property(property="deleted_at", type="string", nullable=true, example=null)
     *                     ),
     *
     *                     @OA\Property(
     *                         property="courts",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="name", type="string", example="Quadra 3"),
     *                             @OA\Property(property="capacity", type="integer", example=10),
     *                             @OA\Property(property="arena_id", type="integer", example=1),
     *                             @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-02T18:46:45.000000Z"),
     *                             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-02T18:46:45.000000Z"),
     *                             @OA\Property(property="deleted_at", type="string", nullable=true, example=null)
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Arena not found",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  type="object",
     *                  @OA\Property(property="success", type="boolean", example=false),
     *                  @OA\Property(property="message", type="string", example="Arena not found.")
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response=401,
     *         description="Tries to access without system login",
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
    public function show(string $id)
    {
        $arena = $this->arenaService->getArenaById($id);
        return success_response($arena);
    }

    /**
     * @OA\Get(
     *     path="/api/arenas/{id}/courts",
     *     summary="Get courts by arena ID",
     *     tags={"Arenas"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the arena",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of courts for the specified arena",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="results",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Quadra 3"),
     *                     @OA\Property(property="capacity", type="integer", example=10),
     *                     @OA\Property(property="arena_id", type="integer", example=1),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-02T18:46:45.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-02T18:46:45.000000Z"),
     *                     @OA\Property(property="deleted_at", type="string", nullable=true, example=null),
     *                     @OA\Property(
     *                         property="timetables",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=12),
     *                             @OA\Property(property="court_id", type="integer", example=1),
     *                             @OA\Property(property="day_of_week", type="integer", example=1),
     *                             @OA\Property(property="start_time", type="string", example="15:00"),
     *                             @OA\Property(property="end_time", type="string", example="16:00"),
     *                             @OA\Property(property="status", type="string", example="busy"),
     *                             @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-03T01:48:04.000000Z"),
     *                             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-27T16:09:05.000000Z")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Arena not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Arena not found.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized access (unauthenticated)",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function courts(string $id): JsonResponse
    {
        $arena = $this->arenaService->getCourts($id);
        return success_response($arena);
    }

    /**
     * @OA\Put(
     *     path="/api/arenas/{id}",
     *     summary="Update an existing arena",
     *     tags={"Arenas"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the arena to update",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Arena X"),
     *             @OA\Property(property="street", type="string", example="Rua A"),
     *             @OA\Property(property="number", type="string", example="123"),
     *             @OA\Property(property="neighborhood", type="string", example="Centro"),
     *             @OA\Property(property="city", type="string", example="São Paulo"),
     *             @OA\Property(property="state", type="string", example="SP"),
     *             @OA\Property(property="zip_code", type="string", example="12345-678"),
     *             @OA\Property(property="admin_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Arena updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                  property="results",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=2),
     *                      @OA\Property(property="admin_id", type="integer", example=2),
     *                      @OA\Property(property="name", type="string", example="Arena 1"),
     *                      @OA\Property(property="street", type="string", example="Rua lagoa"),
     *                      @OA\Property(property="number", type="integer", example=44),
     *                      @OA\Property(property="neighborhood", type="string", example="Bairro"),
     *                      @OA\Property(property="city", type="string", example="Vitoria"),
     *                      @OA\Property(property="state", type="string", example="PE"),
     *                      @OA\Property(property="zip_code", type="string", example="5569955"),
     *                      @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-02T18:46:22.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-02T18:46:22.000000Z"),
     *                      @OA\Property(property="deleted_at", type="string", nullable=true, example=null),
     *
     *                      @OA\Property(
     *                          property="admin",
     *                          type="object",
     *                          nullable=true,
     *                          @OA\Property(property="id", type="integer", example=2),
     *                          @OA\Property(property="name", type="string", example="34343"),
     *                          @OA\Property(property="surname", type="string", example="Mesquita"),
     *                          @OA\Property(property="phone", type="string", example="353535"),
     *                          @OA\Property(property="email", type="string", example="admin@admin.com"),
     *                          @OA\Property(property="email_verified_at", type="string", nullable=true, example=null),
     *                          @OA\Property(property="type", type="string", example="admin"),
     *                          @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-02T18:46:22.000000Z"),
     *                          @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-07T17:20:21.000000Z"),
     *                          @OA\Property(property="deleted_at", type="string", nullable=true, example=null)
     *                      ),
     *
     *                      @OA\Property(
     *                          property="courts",
     *                          type="array",
     *                          @OA\Items(
     *                              type="object",
     *                              @OA\Property(property="id", type="integer", example=1),
     *                              @OA\Property(property="name", type="string", example="Quadra 3"),
     *                              @OA\Property(property="capacity", type="integer", example=10),
     *                              @OA\Property(property="arena_id", type="integer", example=1),
     *                              @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-02T18:46:45.000000Z"),
     *                              @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-02T18:46:45.000000Z"),
     *                              @OA\Property(property="deleted_at", type="string", nullable=true, example=null)
     *                          )
     *                      )
     *                  )
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Arena not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Arena not found.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized access (unauthenticated)",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $arena = $this->arenaService->updateArena($request, $id);
        return success_response($arena);
    }

    /**
     * @OA\Delete(
     *     path="/api/arenas/{id}",
     *     summary="Delete an existing arena",
     *     tags={"Arenas"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the arena to delete",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Arena deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Arena deleted successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Arena not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Arena not found.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized access (unauthenticated)",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $this->arenaService->deleteArena($id);
        return success_response(null, null, Response::HTTP_NO_CONTENT);
    }
}
