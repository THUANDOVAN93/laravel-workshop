<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class GroupsTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_groups_index_page_returns_successful_response(): void
    {
        $this->get(route('groups.index'))
            ->assertOk();
    }

    public function test_groups_index_page_displays_groups(): void
    {
        $groups = Group::factory(3)->create();

        $response = $this->get(route('groups.index'));

        $response->assertOk();

        foreach ($groups as $group) {
            $response->assertSee($group->name);
        }
    }

    public function test_groups_index_page_displays_users_in_group(): void
    {
        $group = Group::factory()->create();
        $user = User::factory()->create();
        $group->users()->attach($user);

        $response = $this->get(route('groups.index'));

        $response->assertOk();
        $response->assertSee($user->name);
        $response->assertSee($user->email);
    }

    public function test_can_add_user_to_group(): void
    {
        $group = Group::factory()->create();
        $user = User::factory()->create();

        $this->post(route('groups.users.add', $group), ['user_id' => $user->id])
            ->assertRedirect(route('groups.index'));

        $this->assertTrue($group->users()->where('user_id', $user->id)->exists());
    }

    public function test_adding_same_user_twice_does_not_duplicate(): void
    {
        $group = Group::factory()->create();
        $user = User::factory()->create();
        $group->users()->attach($user);

        $this->post(route('groups.users.add', $group), ['user_id' => $user->id])
            ->assertRedirect(route('groups.index'));

        $this->assertCount(1, $group->users()->where('user_id', $user->id)->get());
    }

    public function test_add_user_requires_valid_user_id(): void
    {
        $group = Group::factory()->create();

        $this->post(route('groups.users.add', $group), ['user_id' => 9999])
            ->assertSessionHasErrors('user_id');
    }

    public function test_add_user_requires_user_id(): void
    {
        $group = Group::factory()->create();

        $this->post(route('groups.users.add', $group), [])
            ->assertSessionHasErrors('user_id');
    }
}
