<?php

namespace App\Repositories;

use App\Models\Api\Delivery;
use App\Models\Api\Item;
use Exception;
use Illuminate\Support\Facades\Cache;

class ItemRepository implements ItemRepositoryInterface 
{
    public function getAll() 
    {
        return Cache::remember('item_key', 60, function () {
            return Item::with(['units:unit_id,unit_name', 'types:type_id,type_name', 'users:user_id,username,email'])
            // ->select(array_diff(\Schema::getColumnListing('items'), ['image_public_id'])) // Ambil semua kecuali 'te'
            ->get();
        });
    }

    public function getById($id)
    {
        $item = Item::with(['units:unit_id,unit_name', 'types:type_id,type_name', 'users:user_id,username,email'])->find($id);
        if (!$item) {
            return null;
        }
        return $item;
    }

    public function create(array $data)
    {
        return Item::create($data);
    }

    public function createOut(array $data)
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

    public function updateAmount($id, $data)
    {
        $item = Item::find($id);
        if (!$item) {
            return null; // Jika tidak ada user dengan ID tersebut
        }

        try {
            $delivery = new Delivery();
            $delivery->item_id = $data['item_id'];
            $delivery->item_name = $data['item_name'];
            $delivery->user_id = $data['user_id'];
            $delivery->type_name = $data['type_name'];
            $delivery->type_id = $data['type_id'];
            $delivery->unit_name = $data['unit_name'];
            $delivery->unit_id = $data['unit_id'];
            $delivery->amount = $data['amount'];
            $delivery->management_in = $data['management_in'];
            $delivery->management_out = $data['management_out'];
            $delivery->image = $data['image'];
            $delivery->image_public_id = $data['image_public_id'];
            $delivery->save();
            // return dd($data);
    
            $newAmount = $item->amount - $data['amount'];
    
            if ($newAmount < 0) {
                return null;
            }
            $item->update(['amount' => $newAmount]);
            return $item;
        } catch (Exception $e) {
            dd($e->getMessage());
            // return null;
        }
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