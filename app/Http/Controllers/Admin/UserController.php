<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * 会員一覧ページの表示
     */
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        $query = User::query();

        if($keyword) {
            $query->where('name', 'like', "%{$keyword}%")
                ->orWhere('kana', 'like', "%{$keyword}%");
        }

        $users = $query->paginate(10);
        $total = $users->total();

        return view('admin.users.index', compact('users', 'keyword', 'total'));
    }

    /**
     * 会員詳細ページの詳細
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }
}
