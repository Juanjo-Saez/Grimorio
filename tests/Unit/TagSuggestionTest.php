<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Tag;
use App\Services\TagService;
use Tests\TestCase;

class TagSuggestionTest extends TestCase
{
    protected TagService $tagService;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tagService = app(TagService::class);
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_suggests_tags_when_words_appear_in_content()
    {
        // Crear tags
        $tag1 = $this->tagService->getOrCreate($this->user, 'JavaScript');
        $tag2 = $this->tagService->getOrCreate($this->user, 'CSS');
        $tag3 = $this->tagService->getOrCreate($this->user, 'PHP');

        // Contenido que menciona solo JavaScript y CSS
        $content = 'Esta es una guía sobre JavaScript y CSS en el navegador';

        $suggested = $this->tagService->suggestTagsFromContent(
            $this->user,
            title: '',
            content: $content,
            description: ''
        );

        // Debe sugerir JavaScript y CSS pero no PHP
        $suggestionNames = array_column($suggested, 'name');
        $this->assertContains('JavaScript', $suggestionNames);
        $this->assertContains('CSS', $suggestionNames);
        $this->assertNotContains('PHP', $suggestionNames);
    }

    /** @test */
    public function it_handles_case_insensitivity()
    {
        $this->tagService->getOrCreate($this->user, 'JavaScript');

        $content = 'I love javascript programming';

        $suggested = $this->tagService->suggestTagsFromContent(
            $this->user,
            title: '',
            content: $content,
            description: ''
        );

        $this->assertCount(1, $suggested);
        $this->assertEquals('JavaScript', $suggested[0]['name']);
    }

    /** @test */
    public function it_does_not_suggest_partial_word_matches()
    {
        $this->tagService->getOrCreate($this->user, 'Java');
        $this->tagService->getOrCreate($this->user, 'Python');

        // "JavaScript" no debe coincidir con "Java" porque buscamos palabras completas
        $content = 'JavaScript is great, but JavaScript is everywhere';

        $suggested = $this->tagService->suggestTagsFromContent(
            $this->user,
            title: '',
            content: $content,
            description: ''
        );

        // No debe sugerir "Java"
        $suggestionNames = array_column($suggested, 'name');
        $this->assertNotContains('Java', $suggestionNames);
    }

    /** @test */
    public function it_searches_across_title_content_and_description()
    {
        $this->tagService->getOrCreate($this->user, 'Receta');
        $this->tagService->getOrCreate($this->user, 'Cocina');
        $this->tagService->getOrCreate($this->user, 'Pollo');

        $suggested = $this->tagService->suggestTagsFromContent(
            $this->user,
            title: 'Receta de Pollo',
            content: 'Ingredientes: pollo, aceite, sal...',
            description: 'Una guía de cocina para principiantes'
        );

        $suggestionNames = array_column($suggested, 'name');
        $this->assertCount(3, $suggestionNames);
        $this->assertContains('Receta', $suggestionNames);
        $this->assertContains('Cocina', $suggestionNames);
        $this->assertContains('Pollo', $suggestionNames);
    }

    /** @test */
    public function it_returns_empty_array_for_empty_content()
    {
        $this->tagService->getOrCreate($this->user, 'JavaScript');

        $suggested = $this->tagService->suggestTagsFromContent(
            $this->user,
            title: '',
            content: '',
            description: ''
        );

        $this->assertEmpty($suggested);
    }
}
