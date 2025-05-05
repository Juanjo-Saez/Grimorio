<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Note;


class NoteTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_note_has_user_id(): void
    {
        $note = new Note(['filename' => 'Prueba', 'user_id' => 1]);

        $this->assertFalse(empty($note->user_id));
    }
}
