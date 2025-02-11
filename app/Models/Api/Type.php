<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Type extends Model
{
    //

    protected $table = 'types';
    protected $primaryKey = 'type_id';

    protected $fillable = [
        'type_id',
        'type_name'
    ];

    // Relasi dengan model Item (misalnya setiap type bisa memiliki banyak item)
    public function items(): HasMany 
    {
        return $this->hasMany(Item::class, 'type_id');
    }

}
