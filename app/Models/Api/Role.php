<?php

namespace App\Models\Api;

use App\Models\Api\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    //

    protected $table = 'roles';
    protected $primaryKey = 'role_id';

    protected $fillable = [
        'role_id',
        'role_name'
    ];

    // Relasi dengan model Item (misalnya setiap type bisa memiliki banyak item)
    public function users(): HasMany 
    {
        return $this->hasMany(User::class, 'role_id');
    }

}
