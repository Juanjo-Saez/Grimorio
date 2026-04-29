<?php

namespace App\Features\SharedLink\Controllers;

use App\Http\Controllers\Controller;
use App\Features\SharedLink\Services\SharedLinkService;
use App\Models\User;
use Illuminate\Http\Request;

class SharedNoteController extends Controller
{
    protected $sharedLinkService;

    public function __construct(SharedLinkService $sharedLinkService)
    {
        $this->sharedLinkService = $sharedLinkService;
    }

    /**
     * Ver nota compartida por token
     */
    public function show($token)
    {
        $user = auth()->user() ?? auth('api')->user() ?? User::find(1);
        
        $link = $this->sharedLinkService->validateToken($token);

        if (!$link || !$this->sharedLinkService->canView($link, $user)) {
            return response()->json(['error' => 'No encontrado'], 404);
        }

        return response()->json([
            'note' => $link->note,
            'access_level' => $link->access_level,
            'owner' => [
                'id' => $link->owner->id,
                'email' => $link->owner->email,
            ],
        ]);
    }

    /**
     * Actualizar nota compartida
     */
    public function update($token, Request $request)
    {
        $user = auth()->user() ?? auth('api')->user() ?? User::find(1);
        
        $link = $this->sharedLinkService->validateToken($token);

        if (!$link || !$this->sharedLinkService->canEdit($link, $user)) {
            return response()->json(['error' => 'Acceso denegado'], 403);
        }

        $validated = $request->validate([
            'content' => 'nullable|string',
            'description' => 'nullable|string|max:500',
        ]);

        $link->note->update($validated);
        
        return response()->json($link->note);
    }
}
