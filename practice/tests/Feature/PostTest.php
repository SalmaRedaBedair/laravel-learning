<?php

namespace Tests\Feature;

use App\Models\Assignment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    public function test_post_creates_new_assignment()
    {
        $this->post('/assignments', [
            'title' => 'My great assignment',
        ]);
        $this->assertDatabaseHas('assignments', [
            'title' => 'My great assignment',
        ]);
    }
    public function test_list_page_shows_all_assignments()
    {
        $assignment = Assignment::create([
            'title' => 'My great assignment',
        ]);
        $this->get('/assignments')
            ->assertSee('My great assignment');
    }
}
