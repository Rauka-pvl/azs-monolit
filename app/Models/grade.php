<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class grade extends Model
{
    use HasFactory;
    protected $fillable = [
        'azs_id',
        'user_id',
        'grade',
        'photo'
    ];

    protected $table = 'grade';
}
