<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
   protected $model = Company::class;
   
    public function definition(): array
    {
        return [
            'name' => 'テスト', 
            'postal_code' => '0000000', 
            'address' => 'テスト', 
            'representative' => 'テスト', 
            'establishment_date' => 'テスト', 
            'capital' => 'テスト', 'business' => 'テスト', 
            'number_of_employees' => 'テスト',
        ];
    }
}
