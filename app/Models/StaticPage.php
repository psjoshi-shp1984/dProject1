<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // <-- Add this
use Illuminate\Database\Eloquent\SoftDeletes; 


class StaticPage extends Model
{
 
    use HasFactory, SoftDeletes;

    protected $table = 'static_page';

    protected $fillable = [
        'page_name',
        'page_slug',
        'image',
        'image_name',
        'image_hover_text',
        'page_descriptions',
        'status',
    ];
}


