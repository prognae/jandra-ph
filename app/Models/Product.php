<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_name',
        'category',
        'price',
        'shopee_url',
        'main_description',
        'product_information',
        'material_used',
        'main_photo',
        'thumbnail_main_photo',
        'additional_photo_1',
        'thumbnail_additional_photo_1',
        'additional_photo_2',
        'thumbnail_additional_photo_2',
        'additional_photo_3',
        'thumbnail_additional_photo_3'
    ];

    public function category() {
        return $this->belongsTo(Category::class, 'category', 'id');
    }
}
