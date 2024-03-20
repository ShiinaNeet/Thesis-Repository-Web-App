<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class thesis extends Model
{
    use HasFactory;
    use SoftDeletes;
    public function author()
    {
        return $this->belongsToMany(authors::class);
    }


    public function category()
    {
        return $this->belongsToMany(Category::class);
    }

    public function keywords()
    {
        return $this->belongsToMany(Keywords::class);
    }
}
