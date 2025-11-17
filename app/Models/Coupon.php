<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Coupon extends Model
{
use HasFactory;


protected $fillable = [
'code','type','value','min_cart_amount','max_uses','max_uses_per_user','used_count','starts_at','ends_at','active'
];


protected $casts = [
'starts_at' => 'datetime',
'ends_at' => 'datetime',
'active' => 'boolean',
];


public function isActive()
{
$now = Carbon::now();
if (! $this->active) return false;
if ($this->starts_at && $now->lt($this->starts_at)) return false;
if ($this->ends_at && $now->gt($this->ends_at)) return false;
if ($this->max_uses && $this->used_count >= $this->max_uses) return false;
return true;
}


}