<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'private',
        'public',
        'address',
        'wif',
        'currency',
        'chain',
        'total',
    ];
}
