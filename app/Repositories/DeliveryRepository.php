<?php

namespace App\Repositories;

use App\Models\Api\Delivery;
use App\Models\Api\Item;
use Illuminate\Support\Facades\Cache;

class DeliveryRepository implements DeliveryRepositoryInterface 
{
    public function getAll() 
    {
        return Cache::remember('delivery_key', 60, function () {
            return Delivery::all();
        });
    }
    
    public function getById($id) 
    {
        $delivery = Delivery::find($id);
        if (!$delivery) {
            return null;
        }
        return $delivery;
    }

    public function update($id, array $data) 
    {
        $delivery = Delivery::find($id);
        if (!$delivery) {
            return null;
        }
        
        $checkItem = Item::where('item_id', $data['item_id'])->first();
        if(!$checkItem)
        {
            $newItem = Item::create([
                'item_name' => $data['item_name'],
                'item_id' => $data['item_id'],
                'user_id' => $data['user_id'],
                'type_id' => $data['type_id'],
                'unit_id' => $data['unit_id'],
                'amount' => $data['amount'],
                'image' => $data['image'],
                'image_public_id' => $data['image_public_id'],
            ]);
            return $newItem->save();
        }
        
        $newAmount = $checkItem->amount + $data['amount'];
        if ($newAmount < 0) {
            return null;
        }
        $checkItem->update(['amount' => $newAmount]);
        return $checkItem;
    }

    public function delete($id)
    {
        $delivery = Delivery::find($id);
        if (!$delivery) {
            return null; 
        }
        return $delivery->delete();
    }
}