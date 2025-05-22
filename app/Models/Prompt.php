<?php

// app/Models/Prompt.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prompt extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'rating',
        'price',
        'image',
        'category',
        'popular'
    ];

    protected $casts = [
        'popular' => 'boolean',
        'rating' => 'float',
        'price' => 'float',

    ];
}
