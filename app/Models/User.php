<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'id';  
    protected $fillable = [
        'name', 'email', 'password'
    ];

protected static function booted()
{
    static::creating(function ($user) {
        $user->id = static::getSmallestAvailableId();
    });
}

protected static function getSmallestAvailableId()
{
    $maxId = static::max('id');
    $existingIds = static::pluck('id')->flip();

    for ($i = 1; $i <= $maxId + 1; $i++) {
        if (!$existingIds->has($i)) {
            return $i;
        }
    }
    return $maxId ? $maxId + 1 : 1;
}
}
