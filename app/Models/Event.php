<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'organizer',
        'contact',
        'description',
        '_meta',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        '_meta' => 'json',
    ];

    protected $dates = ['deleted_at', 'start_date', 'end_date'];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function visitors()
    {
        return $this->hasMany(EventVisitor::class);
    }

    public function exhibitors()
    {
        return $this->hasMany(EventExhibitor::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
