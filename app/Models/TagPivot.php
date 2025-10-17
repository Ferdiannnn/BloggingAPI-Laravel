<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagPivot extends Model
{
    use HasFactory;

    protected $table="tagpivot";

    protected $fillable = ['blog_id','tag_id'];
}
