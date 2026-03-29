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
        $this->get(route('users.index'))
            ->assertOk();
    }

    public function test_users_index_page_displays_users(): void
    {
        $users = User::factory(3)->create();

        $response = $this->get(route('users.index'));

        $response->assertOk();

        foreach ($users as $user) {
            $response->assertSee($user->name);
            $response->assertSee($user->email);
        }
    }
}
