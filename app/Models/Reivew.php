<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reivew extends Model
{
    use HasFactory;
    protected $table = 'review';
    protected $fillable = [
        'azs_id',
        'user_id',
        'name',
        'phone',
        'grade',
        'comment',
    ];
}
