<?php

namespace App\Repositories;

use App\Models\Api\Role;

class RoleRepository implements RoleRepositoryInterface 
{
    public function getAll() 
    {
        return Role::all();
    }

    public function getById($id)
    {
        return Role::find($id);
    }

    public function create(array $data)
    {
        return Role::create($data);
    }

    public function update($id, array $data)
    {
        $role = Role::find($id);

        $role->update($data);
        return $role;
    }

    public function delete($id)
    {
        $role = Role::find($id);
        return $role->delete();
    }
}