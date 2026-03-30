<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Permission::insert([
            ['auth_code' => 'product:create', 'description' => 'Create product'],
            ['auth_code' => 'product:update', 'description' => 'Update product'],
            ['auth_code' => 'product:update-any', 'description' => 'Update any product'],
            ['auth_code' => 'product:delete', 'description' => 'Delete product'],
            ['auth_code' => 'product:delete-any', 'description' => 'Delete any product'],
            ['auth_code' => 'user:create', 'description' => 'Create user'],
            ['auth_code' => 'permission:create', 'description' => 'Create permission'],
        ]);

        $adminRole = Role::create(['auth_code' => 'admin', 'name' => 'System Administrator']);
        $authorRole = Role::create(['auth_code' => 'author', 'name' => 'Product Author']);
        $editorRole = Role::create(['auth_code' => 'editor', 'name' => 'Product Editor']);

        $adminUser = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'permissions' => [
                'product:create',
                'product:update-any',
                'product:delete-any',
                'user:create',
                'permission:create',
            ],
        ]);

        $adminUser->roles()->attach($adminRole);

        $authorUser = User::factory()->create([
            'name' => 'Author',
            'email' => 'author@example.com',
            'permissions' => [
                'product:create',
                'product:update',
                'product:delete',
            ],
        ]);

        $authorUser->roles()->attach($authorRole);

        $editorUser = User::factory()->create([
            'name' => 'Editor',
            'email' => 'editor@example.com',
            'permissions' => [
                'product:update-any',
                'product:delete-any',
            ],
        ]);

        $editorUser->roles()->attach($editorRole);

        $authorEditorUser = User::factory()->create([
            'name' => 'Author/Editor',
            'email' => 'ae@example.com',
            'permissions' => [
                'product:create',
                'product:update-any',
                'product:delete-any',
            ],
        ]);

        $authorEditorUser->roles()->attach($authorRole);
        $authorEditorUser->roles()->attach($editorRole);

        Product::factory(10)
            ->recycle($authorUser)
            ->create();

        Product::factory(10)
            ->recycle($authorEditorUser)
            ->create();
    }
}
