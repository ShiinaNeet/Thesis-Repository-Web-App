<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class keywords extends Model
{
    use HasFactory; 
    use SoftDeletes;

    public function theses()
    {
        return $this->belongsToMany(Thesis::class, 'thesis_category', 'category_id', 'thesis_id');
    }
}
