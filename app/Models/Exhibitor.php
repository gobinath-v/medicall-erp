<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Import Authenticatable class
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract; // Import Authenticatable interface

class Exhibitor extends Authenticatable implements AuthenticatableContract
{
    use HasFactory;
    use softDeletes;
    use HasApiTokens;

    protected $fillable = [
        'username',
        'name',
        'category_id',
        'proof_type',
        'proof_id',
        'email',
        'website',
        'password',
        'mobile_number',
        'logo',
        'description',
        'known_source',
        'newsletter',
        'registration_type',
        'created_by',
        'updated_by',
        'deleted_by',
        '_meta'
    ];
    protected $casts = [
        '_meta' => 'json',
    ];

    protected $dates = ['deleted_at'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedByUser()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function exhibitorContact()
    {
        return $this->hasOne(ExhibitorContact::class);
    }

    public function eventExhibitors()
    {
        return $this->hasMany(EventExhibitor::class, 'exhibitor_id');
    }

    public function exhibitorProducts()
    {
        return $this->hasMany(ExhibitorProduct::class);
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
