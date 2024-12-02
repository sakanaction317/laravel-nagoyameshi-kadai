<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    //indexアクション(お気に入り一覧ページ)
    public function index()
    {
        $favorite_restaurants = Auth::user()->favorite_restaurants()->orderBy('restaurants_user.created_at', 'desc')->paginate(15);

        return view('favorites.index', compact('favorite_restaurants'));
    }

    // storeアクション(お気に入り追加機能)
    public function store(Restaurant $restaurant)
    {
        Auth::user()->favorite_restaurants()->attach($restaurant->id);
        return redirect()->back()->with('flash_message', 'お気に入りに追加しました。');
    }

    // destroyアクション(お気に入り解除)
    public function destroy(Restaurant $restaurant)
    {
        Auth::user()->favorite_restaurants()->detach($restaurant->id);
        return redirect()->back()->with('flash_message', 'お気に入りを解除しました。');
    }
}
