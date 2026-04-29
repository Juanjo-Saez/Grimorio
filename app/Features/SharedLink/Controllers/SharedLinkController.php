<?php

namespace App\Features\SharedLink\Controllers;

use App\Http\Controllers\Controller;
use App\Features\SharedLink\Services\SharedLinkService;
use App\Models\SharedLink;
use App\Models\Note;
use App\Models\User;
use Illuminate\Http\Request;

class SharedLinkController extends Controller
{
    protected $sharedLinkService;

    public function __construct(SharedLinkService $sharedLinkService)
    {
        $this->sharedLinkService = $sharedLinkService;
    }

    /**
     * Crear link compartido
     */
    public function store(Request $request, $noteId)
    {
        $owner = auth()->user() ?? auth('api')->user() ?? User::find(1);
        
        $validated = $request->validate([
            'recipient_id' => 'required|exists:users,id|different:owner_id',
            'access_level' => 'required|in:read,edit',
        ]);

        try {
            $note = Note::where('id', $noteId)
                ->where('user_id', $owner->id)
                ->firstOrFail();

            $recipient = User::findOrFail($validated['recipient_id']);

            $link = $this->sharedLinkService->createLink(
                $owner,
                $note,
                $recipient,
                $validated['access_level']
            );

            return response()->json([
                'id' => $link->id,
                'token' => $link->token,
                'url' => url("/shared/{$link->token}"),
                'access_level' => $link->access_level,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Listar notas compartidas conmigo
     */
    public function listReceived(Request $request)
    {
        $user = auth()->user() ?? auth('api')->user() ?? User::find(1);
        
        $filters = [];
        if ($request->has('owner_id')) {
            $filters['owner_id'] = $request->get('owner_id');
        }
        if ($request->has('access_level')) {
            $filters['access_level'] = $request->get('access_level');
        }

        $shares = $this->sharedLinkService->getSharedWithMe($user, $filters);
        
        return response()->json($shares);
    }

    /**
     * Listar notas que he compartido
     */
    public function listShared(Request $request, $noteId = null)
    {
        $owner = auth()->user() ?? auth('api')->user() ?? User::find(1);
        
        $shares = $this->sharedLinkService->getSharedByMe($owner, $noteId);
        
        return response()->json($shares);
    }

    /**
     * Revocar acceso
     */
    public function revoke($id)
    {
        $owner = auth()->user() ?? auth('api')->user() ?? User::find(1);
        
        try {
            $this->sharedLinkService->revoke($owner, $id);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Not found'], 404);
        }
    }
}
