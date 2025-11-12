<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
 
    protected $guarded = [];
    //protected $fillable = ['category_id', 'name', 'description', 'price', 'stock', 'image'];

    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function brand(){
        return $this->belongsTo(Brand::class);
    }
    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_size')->wherePivot('status', 1);
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_color')->wherePivot('status', 1);
    }
    public function ratings()
    {
        return $this->hasMany(ProductRating::class);
    }

    public function averageRating()
    {
        return $this->ratings()->avg('rating');
    }

    public function ratingCount()
    {
        return $this->ratings()->count();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // ⭐ Average Rating (Dynamic Attribute)
    public function getAverageRatingAttribute()
    {
        if ($this->ratings->count() > 0) {
            return round($this->ratings->avg('rating'), 1);
        }
        return null;
    }


}
