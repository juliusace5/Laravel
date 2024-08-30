<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPosts extends Model
{
    use HasFactory;

    protected $table = 'blog';

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'title',
        'content',
        'author'
    ];
}
