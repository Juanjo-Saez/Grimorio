<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\SharedLink;
use App\Services\SharedLinkService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SharedLinkController extends Controller
{
    public function __construct(protected SharedLinkService $shares) {}

    public function store(Request $request, Note $note)
    {
        $data = $request->validate([
            'recipient_email' => ['required', 'email'],
            'access_level' => ['required', 'in:read,edit'],
        ]);

        try {
            $this->shares->createShare(Auth::user(), $note, $data['recipient_email'], $data['access_level']);
        } catch (\Exception $e) {
            return back()->withErrors(['share' => $e->getMessage()]);
        }

        return back()->with('success', 'Nota compartida correctamente.');
    }

    public function createPublic(Request $request, Note $note)
    {
        $data = $request->validate([
            'access_level' => ['required', 'in:read,edit'],
        ]);

        try {
            $link = $this->shares->createPublicLink(Auth::user(), $note, $data['access_level']);
            $publicUrl = route('shared.show', ['token' => $link->token], true);
        } catch (\Exception $e) {
            return back()->withErrors(['share' => $e->getMessage()]);
        }

        return back()->with('success', 'Link público generado.')->with('public_link', $publicUrl);
    }

    public function destroy(SharedLink $sharedLink)
    {
        try {
            $this->shares->revokeShare(Auth::user(), $sharedLink);
        } catch (\Exception $e) {
            return back()->withErrors(['share' => $e->getMessage()]);
        }
        return back()->with('success', 'Compartición revocada.');
    }

    public function sharedWithMe()
    {
        $shares = $this->shares->getSharedWithMe(Auth::user());
        return view('shared.index', compact('shares'));
    }

    public function viewShared(string $token)
    {
        // Si no está autenticado, guarda el token en sesión y redirige a login
        if (!Auth::check()) {
            session(['shared_token' => $token]);
            return redirect()->route('login')->with('info', 'Inicia sesión para acceder a la nota compartida.');
        }

        try {
            $link = $this->shares->validateAccess($token, Auth::user());
        } catch (\Exception $e) {
            abort(403, $e->getMessage());
        }
        return view('shared.show', [
            'link' => $link,
            'note' => $link->note->load('tags'),
            'token' => $token,
            'access_level' => $link->access_level,
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
