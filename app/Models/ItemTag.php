<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemTag extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'item_tags';
    public $timestamps = false;
}
