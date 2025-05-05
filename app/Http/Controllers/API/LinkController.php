<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class LinkController extends Controller
{

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
}
