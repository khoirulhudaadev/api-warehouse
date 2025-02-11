<?php

namespace App\Repositories;

use App\Models\Api\User;


class UserRepository implements RoleRepositoryInterface 
{
    public function getAll() 
    {
        $result = User::with('roles')->get();
        return $result;
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

    public function update($id, array $data)
    {
        $user = User::find($id);
        if (!$user) {
            return null; // Jika tidak ada user dengan ID tersebut
        }
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        $user = User::find($id);
        return $user->delete();
    }
}