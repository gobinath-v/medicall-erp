<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventVisitor extends Model
{
    use HasFactory;
    protected $fillable = [
        'event_id',
        'visitor_id',
        'is_visited',
        'product_looking',
        '_meta'
    ];
    protected $casts = [
        'product_looking' => 'json',
        '_meta' => 'json'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_looking', 'id');
    }

    public function getProductNames()
    {
        $productIds = $this->product_looking;

        if (is_array($productIds) && count($productIds) > 0) {
            $productNames = Product::whereIn('id', $productIds)->pluck('name')->toArray();
            return implode(', ', $productNames);
        }

        return null;
    }
}
