<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Journal extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'short_description',
        'authors',
        'release_date'
    ];
    protected $dates = ['deleted_at'];
}
