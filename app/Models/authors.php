<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class authors extends Model
{
    use HasFactory;
    use SoftDeletes;

    // public function theses()
    // {
    //     return $this->belongsToMany(thesis::class);
    // }
}
