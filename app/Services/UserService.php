<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public static function createUser(array $data): User
    {
        $user = User::create($data);
        $user->role = $data['role'] ?? 'admin';

        return $user;
    }

    public static function updateUser(User $user, array $data): User
    {
        $user->update($data);

        return $user;
    }

    public static function deleteUser(User $user): User
    {
        $user->delete();

        return $user;
    }

    public static function validateRole(User $user, string $role): bool
    {
        return $user->role == $role;
    }
}
