<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Restaurant;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // 多対多のリレーションシップを定義
    public function restaurants()
    {
        return $this->belongsToMany(Restaurant::class, 'category_restaurant', 'category_id', 'restaurant_id')->withTimestamps();
    }
}
