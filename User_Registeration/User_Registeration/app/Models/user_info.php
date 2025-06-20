<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_info extends Model
{

    use HasFactory;
    protected $fillable = [
        'full_name',
        'user_name',
        'email',
        'phone',
        'whatsapp_number',
        'image',
        'address',
        'password'
    ];
}
