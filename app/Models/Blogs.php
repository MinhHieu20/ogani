<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blogs extends Model 
{
    use HasFactory;  
    protected $table = 'blogs';

    protected $fillable = [
        'title',
        'thumbnail',
        'description',
        'updated_at',
    ];
}
