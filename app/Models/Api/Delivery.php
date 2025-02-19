<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Delivery extends Model
{
    
    protected $table = 'deliveries';
    protected $primaryKey = 'delivery_id';

    protected $fillable = [
        'delivery_id',
        'item_id',
        'item_name',
        'user_id',
        'amount',
        'management_in',
        'management_out',
        'type_name',
        'type_id',
        'unit_name',
        'unit_id',
        'image', 
        'image_public_id'       
    ];

    protected $guarded = ['delivery_id']; 

}
