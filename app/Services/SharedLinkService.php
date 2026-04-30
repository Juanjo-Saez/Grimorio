<?php

namespace App\Services;

use App\Models\Note;
use App\Models\SharedLink;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SharedLinkService
{
    public function createShare(User $owner, Note $note, string $recipientEmail, string $accessLevel = 'read'): SharedLink
    {
        if ($note->user_id !== $owner->id) {
            throw new AuthorizationException('No puedes compartir una nota que no es tuya.');
        }

        $accessLevel = in_array($accessLevel, ['read', 'edit'], true) ? $accessLevel : 'read';

        $recipient = User::where('email', $recipientEmail)->first();
        if (!$recipient) {
            throw new ModelNotFoundException('No existe ningún usuario con ese email.');
        }
        if ($recipient->id === $owner->id) {
            throw new AuthorizationException('No puedes compartir una nota contigo mismo.');
        }

        return SharedLink::updateOrCreate(
            ['note_id' => $note->id, 'recipient_id' => $recipient->id],
            [
                'owner_id' => $owner->id,
                'token' => bin2hex(random_bytes(32)),
                'access_level' => $accessLevel,
            ]
        );
    }

    public function revokeShare(User $owner, SharedLink $sharedLink): void
    {
        if ($sharedLink->owner_id !== $owner->id) {
            throw new AuthorizationException('No puedes revocar una compartición que no es tuya.');
        }
        $sharedLink->delete();
    }

    public function getSharedWithMe(User $user)
    {
        return SharedLink::with('note', 'owner')
            ->where('recipient_id', $user->id)
            ->latest('created_at')
            ->paginate(10);
    }

    public function validateAccess(string $token, User $user): SharedLink
    {
        $link = SharedLink::with('note', 'owner')->where('token', $token)->firstOrFail();
        if ($link->recipient_id !== $user->id) {
            throw new AuthorizationException('No tienes acceso a esta nota.');
        }
        return $link;
    }
}
