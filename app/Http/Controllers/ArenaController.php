<?php

namespace App\Http\Controllers;

use App\Services\ArenaService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ArenaController
{
    public function __construct(
        private ArenaService $arenaService
    ) { }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return success_response($this->arenaService->getAllArenas());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $arena = $this->arenaService->save($request);
        return success_response($arena, "Arena created successfully", Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $arena = $this->arenaService->getArenaById($id);
        return success_response($arena);
    }
    
    /**
     * Display the courts by arenaId resource.
     */
    public function courts(string $id)
    {
        $arena = $this->arenaService->getCourts($id);
        return success_response($arena);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $arena = $this->arenaService->updateArena($request, $id);
        return success_response($arena);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->arenaService->deleteArena($id);
        return success_response(null, null, Response::HTTP_NO_CONTENT);
    }
}
