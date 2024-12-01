<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\RegularHoliday;
use App\Models\Review;
use App\Models\Reservation;
use Kyslik\ColumnSortable\Sortable;

class Restaurant extends Model
{
    use HasFactory, Sortable;
    
    //一括代入を許可するフィールドを指定します
    protected $fillable = [
        'name',
        'image',
        'description',
        'lowest_price',
        'highest_price',
        'postal_code',
        'address',
        'opening_time',
        'closing_time',
        'seating_capacity',
    ];

    //隠し属性を指定
    protected $hidden = [
        //
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_restaurant', 'restaurant_id', 'category_id')->withTimestamps();
    }

    public function regular_holidays()
    {
        return $this->belongsToMany(RegularHoliday::class, 'regular_holiday_restaurant')->withTimestamps();
    }

    // レビューとのリレーションを設定
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function ratingSortable($query, $direction)
    {
        return $query->withAvg('reviews', 'score')->orderBy('reviews_avg_score', $direction);
    } 

    // 予約機能とのリレーションを設定
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    // 予約数が多い順に並び替えるメソッド
    public function popularSortable($query, $direction)
    {
        return $this->Restaurant::withCount('reservations')->orderBy('reservations_count', $direction);
    }
}

