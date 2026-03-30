<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\ProductAbilities;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Context;

#[Fillable(['name', 'email', 'password', 'permissions'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $attributes = [
        'permissions' => '[]',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'permissions' => 'array',
        ];
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole(string $authCode): bool
    {
        return $this->roles()->where('auth_code', $authCode)->exists();
    }

    public function getAllPermissions()
    {
        if (Auth::user()->id === $this->id && Context::hasHidden('permissions')) {
            return Context::getHidden('permissions');
        }

        $groupPermissions = $this->groups()->with('permissions')->get()
            ->pluck('permissions')->flatten()->pluck('auth_code');

        $permissions = collect($this->permissions);

        return $groupPermissions->merge($permissions)->unique()->map(function ($item) {
            return strtolower($item);
        });
    }

    public function hasPermission($permission): bool
    {
        if ($permission instanceof ProductAbilities) {
            return $this->getAllPermissions()->contains($permission);
        }

        return $this->getAllPermissions()->contains(strtolower($permission));
    }

    public function hasAnyPermission(array $permissions): bool
    {
        $pers = array_map(function ($item) {
            if ($item instanceof ProductAbilities) {
                return $item;
            }

            return strtolower($item);
        }, $permissions);

        return $this->getAllPermissions()->intersect($pers)->isNotEmpty();
    }

    public function wrote(Product $product): bool
    {
        return $this->id === $product->author_id;
    }

    public function didNotWrite(Product $product): bool
    {
        return $this->id !== $product->author_id;
    }
}
