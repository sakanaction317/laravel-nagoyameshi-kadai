<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('user.index', compact('user'));
    }



    public function edit(User $user)
    {
        /*未ログインの場合、ログインページにリダイレクト*/
        if (!Auth::check()) {
            return redirect()->route('login')->with('error_message', 'ログインしてください。');
        }

        /*現在ログイン中のユーザーのIDを取得*/
        $currentUserId = Auth::id();

        /*受け取ったUserインスタンスのIDと現在ログイン中のユーザーのIDを比較*/
        if ($user->id !== $currentUserId) {
            /*IDが一致しない場合、会員情報ページにリダイレクト*/
            return redirect()->route('user.index')->with('error_message', '不正なアクセスです。');
        }
        /*IDが一致する場合、編集ページを表示*/
        return view('user.edit', compact('user'));
    }



    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'kana' => ['required', 'string', 'regex:/\A[ァ-ヴー\s]+\z/u', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'postal_code' => ['required', 'digits:7'],
            'address' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'digits_between:10, 11'],
            'birthday' => ['nullable', 'digits:8'],
            'occupation' => ['nullable', 'string', 'max:255'],
        ]);

        /*現在ログイン中のユーザーのIDを取得*/
        $currentUserId = Auth::id();

        /*受け取ったUserインスタンスのIDと現在ログイン中のユーザーのIDを比較*/
        if ($user->id !== $currentUserId) {
            /*IDが一致しない場合、会員情報ページにリダイレクト*/
            return redirect()->route('user.index')->with('error_message', '不正なアクセスです。');
        }

        /*HTTPリクエストから値を取得し、既存の会員情報を更新*/
        $user->name = $request->input('name');
        $user->kana = $request->input('kana');
        $user->email = $request->input('email');
        $user->postal_code = $request->input('postal_code');
        $user->address = $request->input('address');
        $user->phone_number = $request->input('phone_number');
        $user->birthday = $request->input('birthday');
        $user->occupation = $request->input('occupation');

        /*データベースに保存*/
        $user->save();

        /*保存成功後のリダイレクト・レスポンス*/
        return redirect()->route('user.index')->with('flash_message', '会員情報を編集しました。');
    }
}