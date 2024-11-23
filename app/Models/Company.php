<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'postal_code',
        'address',
        'representative',
        'establishment_date',
        'capital',
        'business',
        'number_of_employees',
    ];
}
