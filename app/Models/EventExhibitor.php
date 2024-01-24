<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventExhibitor extends Model
{
    use HasFactory;
    protected $fillable = [
        'event_id',
        'exhibitor_id',
        'stall_no',
        'is_sponsorer',
        'products',
        'tags',
        'order',
        'cancelled_by',
        'cancelled_at',
        'cancelled_reason',
        'is_active',
        '_meta'
    ];
    protected $casts = [
        'products' => 'json',
        'tags' => 'json',
        '_meta' => 'json',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function exhibitor()
    {
        return $this->belongsTo(Exhibitor::class, 'exhibitor_id');
    }

    public function cancelledByUser()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function products()
    {
        return $this->belongsTo(Product::class,'products','id');
    }

    public function getProductNames()
    {
        $productIds = $this->products;
        if (is_array($productIds) && count($productIds) > 0) {
            $productNames = Product::whereIn('id', $productIds)->pluck('name')->toArray();
            return implode(', ', $productNames);
        }
        return null;
    }
}
