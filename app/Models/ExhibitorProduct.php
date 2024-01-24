<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExhibitorProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'exhibitor_id',
        'product_id',
        '_meta'
    ];

    protected $casts = [
        '_meta' => 'array'
    ];

    public function exhibitor()
    {
        return $this->belongsTo(Exhibitor::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
