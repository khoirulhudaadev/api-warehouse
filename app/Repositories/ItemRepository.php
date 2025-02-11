<?php

namespace App\Repositories;

use App\Models\Api\Item;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\UploadedFile;

class ItemRepository implements ItemRepositoryInterface 
{
    public function getAll() 
    {
        return Item::all();
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