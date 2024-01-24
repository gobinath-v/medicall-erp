<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;
    protected $fillable = [
        'visitor_id',
        'exhibitor_id',
        'product_id',
        'event_id',
    ];

    public function exhibitor()
    {
        return $this->belongsTo(Exhibitor::class, 'exhibitor_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
