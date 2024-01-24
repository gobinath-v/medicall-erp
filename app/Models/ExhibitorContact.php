<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExhibitorContact extends Model
{
    use HasFactory;
    protected $fillable = [
        'exhibitor_id',
        'salutation',
        'name',
        'contact_number',
        'designation'
    ];
    public function exhibitor()
    {
        return $this->belongsTo(Exhibitor::class);
    }
}
