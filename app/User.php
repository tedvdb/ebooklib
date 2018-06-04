<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoleAndPermission;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isAdmin() {
        if($this->admin) return true;
        return false;
    }

    /**
    Check if the user has role.
    @param int|string $role
    @return bool
     */
    public function hasRole($role)
    {
        return $this->getRoles()->contains(function ($value, $key) use ($role) {
            return $role == $value->id || Str::is($role, $value->slug);
        });
    }
}
