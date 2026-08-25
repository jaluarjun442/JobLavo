<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiImportJob extends Model
{
    use HasFactory;


    protected $fillable = [
        'content',
        'status',
    ];


    protected $casts = [
        'content' => 'array',
    ];
}
