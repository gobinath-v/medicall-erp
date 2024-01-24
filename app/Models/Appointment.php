<?php

namespace App\Models;

use App\Models\EventVisitor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'visitor_id',
        'exhibitor_id',
        'scheduled_at',
        'status',
        'notes',
        '_meta',
        'updated_by',
        'cancelled_by',
        'cancelled_at',
    ];

    protected $casts = [
        '_meta' => 'json',
        'scheduled_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

    public function exhibitor()
    {
        return $this->belongsTo(Exhibitor::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function eventVisitorInfo()
    {
        return $this->belongsTo(EventVisitor::class, 'visitor_id', 'visitor_id');
    }
}
