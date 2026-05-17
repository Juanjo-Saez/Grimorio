<?php

namespace App\Http\Controllers;

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

        $token = bin2hex(random_bytes(32));

        // Opción 1: Link abierto (copiable)
        if ($data['share_type'] === 'link') {
            SharedLink::create([
                'note_id' => $note->id,
                'owner_id' => Auth::id(),
                'token' => $token,
                'access_level' => $data['access_level'],
                'recipient_id' => null,
                'recipient_email' => null,
            ]);

            $publicUrl = route('shared.show', ['token' => $token], true);
            return back()->with('success', 'Link generado')->with('share_link', $publicUrl);
        }

        // Opción 2: Email personalizado
        $email = $data['recipient_email'];
        $user = User::where('email', $email)->first();

        SharedLink::create([
            'note_id' => $note->id,
            'owner_id' => Auth::id(),
            'token' => $token,
            'access_level' => $data['access_level'],
            'recipient_id' => $user?->id,
            'recipient_email' => $email,
        ]);

        if ($user) {
            // Usuario existente → enviar email con enlace
            // Mail::to($user->email)->send(new ShareNoteMail($note, $token, $data['access_level']));
            return back()->with('success', "Email enviado a {$email}");
        } else {
            // Usuario nuevo → enviar invitación con registro
            // Mail::to($email)->send(new InviteToShareMail($note, $token));
            return back()->with('success', "Invitación enviada a {$email}");
        }
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

        ]);
    }

    public function updateShared(Request $request, string $token)
    {
        $link = $this->shares->validateAccess($token, Auth::user());
        if ($link->access_level !== 'edit') {
            abort(403, 'Solo lectura.');
        }

        $data = $request->validate([
            'content' => ['nullable', 'string'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $link->note->update([
            'content' => $data['content'] ?? null,
            'description' => $data['description'] ?? null,
        ]);

        return redirect()->route('shared.show', $token)->with('success', 'Cambios guardados.');
    }
}
