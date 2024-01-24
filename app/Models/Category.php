<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $casts = [
        '_meta' => 'array',
    ];

    protected $fillable = [
        'type',
        'name',
        'description',
        'is_active',
        'is_default',
        'parent_id',
        'created_by',
        'updated_by',
        'deleted_by',
        '_meta',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by')->select('id', 'name');
    }

    public function updator()
    {
        return $this->belongsTo(User::class, 'updated_by')->select('id', 'name');
    }
}
