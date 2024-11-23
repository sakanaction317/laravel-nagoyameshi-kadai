<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    public function index()
    {
        $company = Company::first();

        return view('admin.company.index', compact('company'));
    }

    public function edit(Company $company)
    {
        return view('admin.company.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255', 
            'postal_code' => 'required|digits:7', 
            'address' => 'required|string|max:255', 
            'representative' => 'required|string|max:255', 
            'establishment_date' => 'required|string|max:255', 
            'capital' => 'required|string|max:255', 
            'business' => 'required|string|max:255', 
            'number_of_employees' => 'required|string|max:255'
        ]);

        $company->update($validatedData);

        return redirect()->route('admin.company.index')->with('flash_message', '会社概要を編集しました。');
    }

}
