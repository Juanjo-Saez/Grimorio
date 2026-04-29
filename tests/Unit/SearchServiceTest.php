<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Features\Note\Services\SearchService;

class SearchServiceTest extends TestCase
{
    /**
     * Test parseQuery con operador AND
     */
    public function test_parse_query_with_and_operator()
    {
        $searchService = new SearchService();
        $result = $searchService->parseQuery('laravel AND php');
        
        $this->assertCount(2, $result);
        $this->assertContains('laravel', $result);
        $this->assertContains('php', $result);
    }

    /**
     * Test parseQuery con operador OR
     */
    public function test_parse_query_with_or_operator()
    {
        $searchService = new SearchService();
        $result = $searchService->parseQuery('laravel OR php');
        
        $this->assertCount(2, $result);
        $this->assertContains('laravel', $result);
        $this->assertContains('php', $result);
    }

    /**
     * Test parseQuery sin operadores
     */
    public function test_parse_query_without_operators()
    {
        $searchService = new SearchService();
        $result = $searchService->parseQuery('laravel php programming');
        
        $this->assertCount(3, $result);
        $this->assertContains('laravel', $result);
        $this->assertContains('php', $result);
        $this->assertContains('programming', $result);
    }

    /**
     * Test parseQuery vacío
     */
    public function test_parse_query_empty()
    {
        $searchService = new SearchService();
        $result = $searchService->parseQuery('');
        
        $this->assertCount(0, $result);
    }

    /**
     * Test parseQuery con espacios múltiples
     */
    public function test_parse_query_with_multiple_spaces()
    {
        $searchService = new SearchService();
        $result = $searchService->parseQuery('laravel    php     programming');
        
        $this->assertCount(3, $result);
    }
}
