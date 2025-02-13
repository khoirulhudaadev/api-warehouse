<?php

namespace App\Repositories;

use App\Models\Api\Item;
use Illuminate\Support\Facades\Cache;

class ItemRepository implements ItemRepositoryInterface 
{
    public function getAll() 
    {
        return Cache::remember('item_key', 60, function () {
            return Item::with(['units', 'types'])
            ->select(array_diff(\Schema::getColumnListing('items'), ['image_public_id'])) // Ambil semua kecuali 'te'
            ->get();
        });
    }

    public function getById($id)
    {
        $item = Item::with(['units', 'types'])->find($id);
        if (!$item) {
            return null;
        }
        return $item;
    }

    public function create(array $data)
    {
        return Item::create($data);
    }

    public function update($id, array $data)
    {
        $item = Item::find($id);
        if (!$item) {
            return null; // Jika tidak ada user dengan ID tersebut
        }
        $item->update($data);
        return $item;
    }

    public function delete($id)
    {
        $item = Item::find($id);
        if (!$item) {
            return null; // Jika tidak ada user dengan ID tersebut
        }
        return $item->delete();
    }
}