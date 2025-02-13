<?php

namespace App\Repositories;

use App\Models\Api\Type;
use Illuminate\Support\Facades\Cache;

class TypeRepository implements TypeRepositoryInterface 
{
    public function getAll() 
    {
        return Cache::remember('type_key', 60, function () {
            return Type::all();
        });
    }

    public function getById($id)
    {
        return Type::find($id);
    }


    public function create(array $data)
    {
        return Type::create($data);
    }

    public function update($id, array $data)
    {
        $type = Type::find($id);
        $type->update($data);
        return $type;
    }

    public function delete($id)
    {
        $type = Type::find($id);
        return $type->delete();
    }
}