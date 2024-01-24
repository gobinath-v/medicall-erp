<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'pincode',
        'city',
        'state',
        'country',
        'addressable_id',
        'addressable_type',
    ];

    public function addressable()
    {
        return $this->morphTo();
    }

}
