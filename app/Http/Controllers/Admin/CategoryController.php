<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // カテゴリ一覧機能
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $query = Category::query();

        if ($keyword) {
            $query->where('name', 'LIKE', "%{$keyword}%");
        }

        $categories = $query->paginate(15);
        $total = $categories->count();

        return view('admin.categories.index', compact('categories', 'total', 'keyword'));
    }

    // カテゴリ登録機能
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Category::create($validatedData);

        return redirect()->route('admin.categories.index')->with('flash_message', 'カテゴリを登録しました。');
    }

    // カテゴリ更新機能
    public function update(Request $request, Category $category)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update($validatedData);

        return redirect()->route('admin.categories.index')->with('flash_message', 'カテゴリを編集しました。');
    }

    // カテゴリ削除機能
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')->with('flash_message', 'カテゴリを削除しました。');
    }
}
