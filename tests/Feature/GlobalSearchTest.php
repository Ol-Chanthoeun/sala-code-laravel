<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GlobalSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_requires_authentication(): void
    {
        $this->getJson(route('search', ['q' => 'Laravel']))
            ->assertUnauthorized();
    }

    public function test_authenticated_search_returns_published_database_results(): void
    {
        $user = User::factory()->create();

        Course::create([
            'title' => 'Laravel Fundamentals',
            'slug' => 'laravel-fundamentals',
            'short_description' => 'Learn Laravel.',
            'description' => 'Learn Laravel.',
            'programming_language' => 'PHP',
            'difficulty_level' => 'Beginner',
            'status' => 'published',
            'price' => 'Free',
        ]);

        Course::create([
            'title' => 'Private Laravel Notes',
            'slug' => 'private-laravel-notes',
            'short_description' => 'Not published.',
            'description' => 'Not published.',
            'programming_language' => 'PHP',
            'difficulty_level' => 'Beginner',
            'status' => 'draft',
            'price' => 'Free',
        ]);

        $this->actingAs($user)
            ->getJson(route('search', ['q' => 'Laravel']))
            ->assertOk()
            ->assertJsonPath('groups.0.label', 'Courses')
            ->assertJsonPath('groups.0.items.0.title', 'Laravel Fundamentals')
            ->assertJsonCount(1, 'groups.0.items');
    }
}
