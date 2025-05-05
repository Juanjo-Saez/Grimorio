<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class LinkController extends Controller
{

    public function login (Request $request) {
        $credentials = $request->only('email','password');

        if ($token = JWTAuth::attempt($credentials)) {
            return response()->json(['token' => $token]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function getLink(int $note_id) {

        $note = Note::findOrFail($note_id);

        if ($note->link) {
            return response()->json($note->link, 200);
        }

        Link::create([
            'note_id' => $note->id,
            'read_only' => true,
        ]);

        $url = "/shared/$note_id";
        return response()->json($url, 201);
    }

    public function index()
    {
        Auth::loginUsingId(3);
        return Link::whereHas('note', function ($query) {
            $query->where('user_id', Auth::id());
        })->get();
    }

    public function store(Request $request, Note $note)
    {
        Auth::loginUsingId(1);

        $validated = $request->validate([
            'note_id' => 'required|exists:notes,id',
            'read_only' => 'required|boolean',
            'expiry_date' => 'nullable|date|after:now',
        ]);

        $note = Auth::user()->notes()->findOrFail($validated['note_id']);

        $link = Link::create([
            'note_id' => $note->id,
            'read_only' => $validated['read_only'],
            'expiry_date' => $validated['expiry_date'] ? Carbon::parse($validated['expiry_date']) : null,
        ]);
    
        return response()->json($link, 201);
    }

    public function show(Link $link)
    {

        return response()->json($link);
    }

    public function update(Request $request, Link $link)
    {

        $validated = $request->validate([
            'read_only' => 'sometimes|boolean',
            'expiry_date' => 'nullable|date|after:now',
        ]);

        $link->update($validated);

        return response()->json($link);
    }

    public function destroy(Link $link)
    {

        $link->delete();

        return response()->json(['message' => 'Link eliminado correctamente.']);
    }
}
