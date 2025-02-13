<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{

    //

    protected $table = 'items';
    protected $primaryKey = 'item_id';

    protected $fillable = [
        'item_id',
        'item_name',
        'type_id',
        'unit_id',
        'amount',
        'image', 
        'image_public_id'       
    ];

    public function types(): BelongsTo 
    {
        return $this->belongsTo(Type::class, 'type_id')->select('type_id', 'type_name');
    }

    public function units(): BelongsTo 
    {
        return $this->belongsTo(Unit::class, 'unit_id')->select('unit_id', 'unit_name');
    }
}
