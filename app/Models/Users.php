<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Users extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    const TYPE_ADMIN     = 0;
    const TYPE_ASSISTANTLIBRARIAN = 1;
    const TYPE_LIBRARIAN  = 2;
    const TYPE_STUDENT      = 3;

    protected $fillable = [
        'user_type',
        'email',
        'userID',
        'password',
    ];
    protected $hidden = [
        'password',
    ];

   
}
