<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\User\UserCollectionResource;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request): UserCollectionResource
    {
        $users = User::filter($request->all())->simplePaginateFilter(10);

        return new UserCollectionResource($users);
    }

    public function store(RegisterRequest $request): UserResource
    {
        $data = $request->validated();
        $user = UserService::createUser($data);

        return new UserResource($user);
    }

    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    public function update(User $user, UpdateUserRequest $request): UserResource
    {
        $data = $request->validated();
        $user = UserService::updateUser($user, $data);

        return new UserResource($user);
    }

    public function delete(User $user): UserResource
    {
        UserService::deleteUser($user);

        return new UserResource($user);
    }
}
