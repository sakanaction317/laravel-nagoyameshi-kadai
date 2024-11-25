<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // indexアクション（会員情報ページ）
    public function index()
    {
        $user = Auth::user();

        return view('user.index', compact('user'));
    }

    // editアクション（会員情報編集ページ）
    public function edit(User $user)
        {
            if ($user->id !== Auth::id()) {
                return redirect()->route('user.index')->with('error_message', '不正なアクセスです。');
            }

            return view('user.edit', compact('user'));
        }

    // updateアクション（会員情報更新機能）
    public function update(Request $request, User $user)
    {
        if ($user->id !== Auth::id()) {
            return redirect()->route('user.index')->with('error_message', '不正なアクセスです。');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'kana' => 'required|string|max:255|regex:/[ァ-ヶー]+$/u',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'postal_code' => 'required|digits:7', 
            'address' => 'required|string|max:255', 
            'phone_number' => 'required|digits_between:10,11', 
            'birthday' => 'nullable|digits:8', 
            'occupation' => 'nullable|string|max:255',
        ]);

        $user->update($validatedData);

        return redirect()->route('user.index')->with('flash_message', '会員情報を編集しました。');
    }
}

