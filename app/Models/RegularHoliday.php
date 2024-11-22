<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Restaurant;

class RegularHoliday extends Model
{
    use HasFactory;

    protected $fillable = [
        'day',
        'day_index',
    ];

    public function restaurants()
    {
        return $this->belongsToMany(Restaurant::class, 'regular_holiday_restaurant')->withTimestamps();
    }
}
