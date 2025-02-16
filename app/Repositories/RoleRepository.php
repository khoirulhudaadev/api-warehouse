<?php

namespace App\Repositories;

use App\Models\Api\Role;
use Illuminate\Support\Facades\Cache;

class RoleRepository implements RoleRepositoryInterface 
{
    public function getAll() 
    {
        return Cache::remember('role_key', 60, function () {
            return Role::all();
        });
    }

    public function getById($id)
    {
        return Role::find($id);
    }

    public function create(array $data)
    {
        return Role::create($data);
    }

    public function update($id, $data)
    {
        $role = Role::find($id);
        if (!$role) {
            return null;
        }
        $role->update($data);
        return $role;
    }

    public function delete($id)
    {
        $role = Role::find($id);
        return $role->delete();
    }
}