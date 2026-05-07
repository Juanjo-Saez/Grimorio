<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Note;

class NoteTest extends TestCase
{
    public function test_note_is_fillable_with_expected_fields(): void
    {
        $note = new Note([
            'user_id' => 1,
            'title' => 'Prueba',
            'content' => 'Contenido',
            'description' => 'Resumen',
        ]);

        $this->assertSame(1, $note->user_id);
        $this->assertSame('Prueba', $note->title);
        $this->assertSame('Contenido', $note->content);
        $this->assertSame('Resumen', $note->description);
    }
}
