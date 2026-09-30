<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    protected $table = 'foods';
    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'category',
        'price',
        'description',
        'image',
    ];
}