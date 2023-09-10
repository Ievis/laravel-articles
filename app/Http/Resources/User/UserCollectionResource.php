<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollectionResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->with = [
            'success' => true
        ];

        return $this->collection->map(function ($user) {
            return [
                'id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'is_active' => $user->is_active
            ];
        })->toArray();
    }
}
