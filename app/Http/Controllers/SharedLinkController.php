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
        try {
            $link = $this->shares->validateAccess($token, Auth::user());
        } catch (\Exception $e) {
            abort(403, $e->getMessage());
        }
        return view('shared.show', ['link' => $link, 'note' => $link->note->load('tags')]);
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
