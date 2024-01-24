<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'image',
        'tags',
        '_meta',
        'created_by',
        'updated_by',
        'deleted_by',

    ];

    protected $casts = [
        'tags' => 'array',
        '_meta' => 'array',
    ];

    public function categoryName()
    {
        return $this->belongsTo(Category::class, 'category_id')->where('type', 'product_type');
    }
    public function exhibitorProduct(){
        return $this->hasMany(ExhibitorProduct::class);
    }
}
