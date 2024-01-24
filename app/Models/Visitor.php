<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model; // Import Authenticatable class
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\SoftDeletes; // Import Authenticatable interface

class Visitor extends Authenticatable implements AuthenticatableContract
{
    use SoftDeletes;
    use HasFactory;
    use HasApiTokens;

    protected $fillable = [
        'username',
        'password',
        'salutation',
        'name',
        'mobile_number',
        'email',
        'category_id',
        'organization',
        'designation',
        'known_source',
        'reason_for_visit',
        'newsletter',
        'proof_type',
        'proof_id',
        'registration_type',
        '_meta',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    protected $casts = [
        '_meta' => 'json',
    ];
    protected $dates = ['deleted_at'];

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function eventVisitors()
    {
        return $this->hasMany(EventVisitor::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
