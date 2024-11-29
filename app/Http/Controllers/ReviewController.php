<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Restaurant;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Restaurant $restaurant)
    {
        if (Auth::user()->subscribed('premium_plan')) {
            $reviews = Review::where('restaurant_id', $restaurant->id)->orderBy('created_at', 'desc')->paginate(5);
        }else {
            $reviews = Review::where('restaurant_id', $restaurant->id)->orderBy('created_at', 'desc')->paginate(3);
        }

        return view('reviews.index', compact('reviews', 'restaurant'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Restaurant $restaurant)
    {
        return view('reviews.create', compact('restaurant'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Restaurant $restaurant)
    {
        $validatedData = $request->validate([
            'score' => 'required|integer|between:1,5',
            'content' => 'required',
        ]);

        $review = new Review();
        $review->content = $request->input('content');
        $review->score = $request->input('score');
        $review->restaurant_id = $restaurant->id;
        $review->user_id = $request->user()->id;
        $review->save();

        return redirect()->route('restaurants.reviews.index', ['restaurant' => $restaurant->id])->with('flash_message', 'レビューを投稿しました。');
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Restaurant $restaurant, Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            return redirect()->route('restaurant.reviews.index', ['restaurant' => $restaurant->id])->with('error_message', '不正なアクセスです。');
        }

        return view('reviews.edit', compact('review', 'restaurant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Restaurant $restaurant, Review $review)
    {
        $validatedData = $request->validate([
            'score' => 'required|integer|between:1,5',
            'content' => 'required',
        ]);

        if ($review->user_id !== Auth::id()) {
            return redirect()->route('restaurant.reviews.index', ['restaurant' => $restaurant->id])->with('error_message', '不正なアクセスです。');
        }

        $review->score = $request->input('score');
        $review->content = $request->input('content');
        $review->save();

        return redirect()->route('restaurants.reviews.index', ['restaurant' => $restaurant->id])
            ->with('flash_message', 'レビューを編集しました。');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Restaurant $restaurant, Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            return redirect()->route('restaurants.reviews.index', ['restaurant' => $restaurant->id])->with('error_message', '不正なアクセスです。');
        }

        $review->delete();

        return redirect()->route('restaurants.reviews.index', ['restaurant' => $restaurant->id])
            ->with('flash_message', 'レビューを削除しました。');
    }
}
