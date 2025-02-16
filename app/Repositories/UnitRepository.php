<?php

namespace App\Repositories;

use App\Models\Api\Unit;
use Illuminate\Support\Facades\Cache;

class UnitRepository implements UnitRepositoryInterface 
{

    public function getAll() 
    {
        return Cache::remember('unit_key', 60, function () {
            return Unit::all();
        });
    }

    public function getById($id)
    {
        return Unit::find($id);
    }


    public function create(array $data)
    {
        return Unit::create($data);
    }

    public function update($id, $data)
    {
        $unit = Unit::find($id);
        if (!$unit) {
            return null; 
        }
        $unit->update($data);
        return $unit;
    }

    public function delete($id)
    {
        $unit = Unit::find($id);
        return $unit->delete();
    }
}