<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public static function createUser()
    {

    }

    public static function updateUser()
    {

    }

    public static function deleteUser()
    {

    }

    public static function validateRole(User $user, string $role): bool
    {
        return $user->role == $role;
    }
}
