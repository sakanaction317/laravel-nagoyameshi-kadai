<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RestaurantController extends Controller
{
    //店舗一覧ページ
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        $query = Restaurant::query();

        if ($keyword) {
            $query->where('name', 'like', "%{$keyword}%");
        }

        $restaurants = $query->paginate(15);
        $total = $query->count();

        return view('admin.restaurants.index', compact('restaurants', 'keyword', 'total'));
    }

    //店舗詳細ページ
    public function show(Restaurant $restaurant)
    {
        return view('admin.restaurants.show', compact('restaurant'));
    }

    //店舗登録ページ
    public function create()
    {
        return view('admin.restaurants.create');
    }

    //店舗登録機能
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'image|max:2048',
            'description' => 'required|string',
            'lowest_price' => 'required|integer|min:0|lte:highest_price',
            'highest_price' => 'required|integer|min:0|gte:lowest_price', 
            'postal_code' => 'required|numeric|digits:7',
            'address' => 'required|string|max:255',
            'opening_time' => 'required|date_format:H:i|before:closing_time',
            'closing_time' => 'required|date_format:H:i|after:opening_time',
            'seating_capacity' => 'required|integer|min:0',
        ]);

        //ファイルの処理
        if ($request->hasFile('image')) {
            //アップロードされたファイル(name=image)をstorage/app/public/restaurantsフォルダに保存し、戻り値を変数$imageに代入する
            $image = $request->file('image')->store('public/restaurants');
            //ファイルパスからファイル名のみを取得し、Restaurantインスタンスのimageプロパティに代入する
            $validatedData['image'] = basename($image);
        }else {
            //Restaurantインスタンスのimageプロパティに空文字を代入する
            $validatedData['image'] = '';
        }

        Restaurant::create($validatedData);

        return redirect()->route('admin.restaurants.index')->with('flash_message', '店舗を登録しました。');
    }

    //店舗編集ページ
    public function edit(Restaurant $restaurant)
    {
        return view('admin.restaurants.edit', compact('restaurant'));
    }

    //店舗更新機能
    public function update(Request $request, Restaurant $restaurant) 
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'image|max:2048',
            'description' => 'required|string',
            'lowest_price' => 'required|integer|min:0|lte:highest_price',
            'highest_price' => 'required|integer|min:0|gte:lowest_price', 
            'postal_code' => 'required|numeric|digits:7',
            'address' => 'required|string|max:255',
            'opening_time' => 'required|before:closing_time',
            'closing_time' => 'required|after:opening_time',
            'seating_capacity' => 'required|integer|min:0',
        ]);

        //ファイルの処理
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('public/restaurants');
            $validatedData['image'] = basename($image);
        }

        $restaurant->update($validatedData);

        return redirect()->route('admin.restaurants.show', $restaurant)->with('flash_message', '店舗を編集しました。');
    }

    //店舗削除機能
    public function destroy(Restaurant $restaurant)
    {
        if ($restaurant->image) {
            Storage::delete('public/restaurant' . $restaurant->image); //画像ファイルを削除
        }
        $restaurant->delete();

        return redirect()->route('admin.restaurants.index')->with('flash_message', '店舗を削除しました。');
    }
}
