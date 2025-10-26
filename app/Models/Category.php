<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'status', 'created_by', 'updated_by', 'updated_at'];
    
    public function products(){
        return $this->hasMany(Product::class);
    }
}
