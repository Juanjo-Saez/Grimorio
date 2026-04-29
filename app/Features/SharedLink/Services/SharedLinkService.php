<?php

namespace App\Features\SharedLink\Services;

use App\Models\SharedLink;
use App\Models\Note;
use App\Models\User;

class SharedLinkService
{
    /**
     * Crear un link compartido
     */
    public function createLink(User $owner, Note $note, User $recipient, string $accessLevel = 'read'): SharedLink
    {
        // Verificar que nota pertenece a owner
        if ($note->user_id !== $owner->id) {
            throw new \Exception('Nota no pertenece al usuario');
        }

        $token = bin2hex(random_bytes(32));

        return SharedLink::create([
            'note_id' => $note->id,
            'owner_id' => $owner->id,
            'recipient_id' => $recipient->id,
            'token' => $token,
            'access_level' => $accessLevel,
        ]);
    }

    /**
     * Validar token y obtener link
     */
    public function validateToken(string $token): ?SharedLink
    {
        return SharedLink::where('token', $token)
            ->with(['note', 'owner', 'recipient'])
            ->first();
    }

    /**
     * Verificar si usuario puede ver nota
     */
    public function canView(SharedLink $sharedLink, User $user): bool
    {
        return $sharedLink->recipient_id === $user->id;
    }

    /**
     * Verificar si usuario puede editar nota
     */
    public function canEdit(SharedLink $sharedLink, User $user): bool
    {
        return $sharedLink->recipient_id === $user->id 
            && $sharedLink->access_level === 'edit';
    }

    /**
     * Revocar acceso
     */
    public function revoke(User $owner, $sharedLinkId): bool
    {
        $link = SharedLink::where('id', $sharedLinkId)
            ->where('owner_id', $owner->id)
            ->firstOrFail();

        return $link->delete();
    }

    /**
     * Listar notas compartidas conmigo
     */
    public function getSharedWithMe(User $recipient, array $filters = [])
    {
        $query = SharedLink::where('recipient_id', $recipient->id)
            ->with(['note', 'owner']);

        if (isset($filters['owner_id'])) {
            $query->where('owner_id', $filters['owner_id']);
        }

        if (isset($filters['access_level'])) {
            $query->where('access_level', $filters['access_level']);
        }

        return $query->latest()->paginate(20);
    }

    /**
     * Listar notas que he compartido
     */
    public function getSharedByMe(User $owner, $noteId = null)
    {
        $query = SharedLink::where('owner_id', $owner->id)
            ->with(['note', 'recipient']);

        if ($noteId) {
            $query->where('note_id', $noteId);
        }

        return $query->latest()->get();
    }
}
