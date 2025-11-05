<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'description', 'slug', 'image', 'hot', 'carousal', 'featured', 'status', 'created_by', 'updated_by', 'updated_at'];
    
    public function products(){
        return $this->hasMany(Product::class);
    }
}
