<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    //

    protected $table = 'units';
    protected $primaryKey = 'unit_id';

    protected $fillable = [
        'unit_id',
        'unit_name'
    ];

    protected $guarded = ['unit_id']; 

    // Relasi dengan model Item (misalnya setiap type bisa memiliki banyak item)
    public function items(): HasMany 
    {
        return $this->hasMany(Item::class, 'unit_id');
    }

}
