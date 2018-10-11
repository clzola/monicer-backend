<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $fillable = [
        'name',
        'discount1',
        'discount2',
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'discount1' => 'integer',
        'discount2' => 'integer',
    ];
}
