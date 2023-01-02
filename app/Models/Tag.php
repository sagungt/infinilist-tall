<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Tag $tag) {
            $tag->name = '#' . $tag->name;
        });

        static::updating(function (Tag $tag) {
            if ($tag->isDirty(['name'])) {
                $tag->password = '#' . $tag->password;
            }
        });
    }
}
