<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    // restaurantとのリレーションを設定
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    // userとのリレーションを設定
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
