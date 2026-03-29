<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_index_page_returns_successful_response(): void
    {
        $response = $this->get('/users');

        $response->assertStatus(200);
    }

    public function test_users_index_page_displays_users(): void
    {
        $users = User::factory(3)->create();

        $response = $this->get('/users');

        $response->assertStatus(200);
        foreach ($users as $user) {
            $response->assertSee($user->name);
            $response->assertSee($user->email);
        }
    }
}
