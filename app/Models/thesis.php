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
        return $this->belongsToMany(authors::class, 'thesis_author', 'thesis_id', 'author_id');
    }


    public function category()
    {
        return $this->belongsToMany(Category::class, 'thesis_category', 'thesis_id', 'category_id');
    }

    public function keywords()
    {
        return $this->belongsToMany(Keywords::class, 'thesis_keyword', 'thesis_id', 'keyword_id');
    }
}
