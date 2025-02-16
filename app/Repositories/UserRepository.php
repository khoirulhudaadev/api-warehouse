<?php

namespace App\Repositories;

use App\Models\Api\User;
use Illuminate\Support\Facades\Cache;


class UserRepository implements RoleRepositoryInterface 
{
    public function getAll() 
    {
        return Cache::remember('user_key', 60, function () {
            $result = User::with('role')->get();
            return $result;
        });
    }

    public function getById($id)
    {
        return User::find($id);
    }

    public function getByEmail(string $data)
    {
        return User::where('email', $data)->first();
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update($id, $data)
    {
        $user = User::find($id);
        if (!$user) {
            return null; 
        }
        $user->update(['username' => $data['username']]);
        return $user;
    }
    
    public function delete($id)
    {
        $user = User::find($id);
        return $user->delete();
    }
}