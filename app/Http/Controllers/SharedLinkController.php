<?php

namespace App\Http\Controllers;

use App\Mail\InviteToShareMail;
use App\Mail\ShareNoteMail;
use App\Models\Note;
use App\Models\SharedLink;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SharedLinkController extends Controller
{
    /**
     * Crear nuevo enlace compartido (link abierto o email personalizado)
     */
    public function store(Request $request, Note $note)
    {
        $this->authorize('update', $note);

        $data = $request->validate([
            'share_type' => ['required', 'in:link,email'],
            'recipient_email' => ['nullable', 'email'],
            'access_level' => ['required', 'in:read,edit'],
        ]);

        // Opción 1: Link abierto (copiable)
        if ($data['share_type'] === 'link') {
            // Reutilizar link público si ya existe para este access_level
            $link = SharedLink::where('note_id', $note->id)
                ->where('recipient_id', null)
                ->where('access_level', $data['access_level'])
                ->first();

            if (!$link) {
                $link = SharedLink::create([
                    'note_id' => $note->id,
                    'owner_id' => Auth::id(),
                    'token' => bin2hex(random_bytes(32)),
                    'access_level' => $data['access_level'],
                    'recipient_id' => null,
                    'recipient_email' => null,
                ]);
            }

            $publicUrl = route('shared.show', ['token' => $link->token], true);
            
            // Si es AJAX, devolver JSON
            if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => 'Link generado',
                    'share_link' => $publicUrl
                ]);
            }
            
            return back()->with('success', 'Link generado')->with('share_link', $publicUrl);
        }

        // Opción 2: Email personalizado
        $email = $data['recipient_email'];
        $user = User::where('email', $email)->first();

        // Crear o actualizar compartición (evitar duplicados)
        if ($user) {
            $share = SharedLink::firstOrCreate(
                ['note_id' => $note->id, 'recipient_id' => $user->id],
                [
                    'owner_id' => Auth::id(),
                    'token' => bin2hex(random_bytes(32)),
                    'recipient_email' => $email,
                ]
            );
            $share->update(['access_level' => $data['access_level']]);
            $message = "Compartición actualizada. El usuario recibirá notificación vía email (próximamente)";
        } else {
            $share = SharedLink::firstOrCreate(
                ['note_id' => $note->id, 'recipient_email' => $email],
                [
                    'owner_id' => Auth::id(),
                    'token' => bin2hex(random_bytes(32)),
                ]
            );
            $share->update(['access_level' => $data['access_level']]);
            $message = "Invitación creada. El usuario recibirá el enlace vía email (próximamente)";
        }

        if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['success' => $message]);
        }
        
        return back()->with('success', $message);
    }

    /**
     * Ver nota compartida (link abierto o personalizado)
     */
    public function viewShared(string $token)
    {
        $link = SharedLink::where('token', $token)->firstOrFail();

        // Lectura → Acceso público
        if ($link->access_level === 'read') {
            return view('shared.show', [
                'note' => $link->note->load('tags'),
                'link' => $link,
                'token' => $token,
                'access_level' => 'read',
            ]);
        }

        // Edición → Requiere autenticación
        if ($link->access_level === 'edit') {
            if (!Auth::check()) {
                session(['share_token' => $token]);
                return redirect()->route('login', ['share_token' => $token])
                    ->with('info', 'Inicia sesión para editar la nota compartida');
            }

            // Vincular el token al usuario si aún no está vinculado
            if (!$link->recipient_id) {
                $link->update(['recipient_id' => Auth::id()]);
            } elseif ($link->recipient_id !== Auth::id()) {
                abort(403, 'Este enlace ya fue reclamado por otro usuario');
            }

            return view('shared.show', [
                'note' => $link->note->load('tags'),
                'link' => $link,
                'token' => $token,
                'access_level' => 'edit',
            ]);
        }
    }

    /**
     * Actualizar nota compartida (solo si access_level === 'edit')
     */
    public function updateShared(Request $request, string $token)
    {
        $link = SharedLink::where('token', $token)->firstOrFail();

        if ($link->access_level !== 'edit') {
            abort(403, 'Solo lectura');
        }

        if (Auth::id() !== $link->recipient_id) {
            abort(403, 'No tienes permiso');
        }

        $data = $request->validate([
            'content' => ['nullable', 'string'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $link->note->update([
            'content' => $data['content'] ?? null,
            'description' => $data['description'] ?? null,
        ]);

        return redirect()->route('shared.show', $token)->with('success', 'Cambios guardados');
    }

    /**
     * Revocar compartición (solo owner)
     */
    public function destroy(SharedLink $sharedLink)
    {
        $this->authorize('delete', $sharedLink);
        $sharedLink->delete();

        return back()->with('success', 'Compartición revocada');
    }

    /**
     * Listado de notas compartidas conmigo
     */
    public function sharedWithMe()
    {
        $shares = SharedLink::where('recipient_id', Auth::id())
            ->with('note', 'owner')
            ->latest('created_at')
            ->paginate(10);

        return view('shared.index', compact('shares'));
    }
}
