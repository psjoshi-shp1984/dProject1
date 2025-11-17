<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <-- Add this
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Slider extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'subtitle', 'image', 'link', 'type', 'status', 'sort_order'
    ];
}
