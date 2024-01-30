<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AssignmentTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    public function test_post_creates_new_assignment()
    {
        $this->post('/posts', [
            'title' => 'My great assignment',
        ]);
        $this->assertDatabaseHas('posts', [
            'title' => 'My great assignment',
        ]);
    }
}
