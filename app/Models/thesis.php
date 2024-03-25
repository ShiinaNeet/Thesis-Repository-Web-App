<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class thesis extends Model
{
    use HasFactory;
    use SoftDeletes;
    // public function authors()
    // {
    //     return $this->belongsToMany(authors::class);
    // }
    // public function categories()
    // {
    //     return $this->belongsToMany(category::class);
    // }
    // public function keywords()
    // {
    //     return $this->belongsToMany(keywords::class);
    // }
}
