<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    // 会社概要ページ
    public function index()
    {
        $company = Company::first();

        return view('company.index', compact('company'));
    }
}
