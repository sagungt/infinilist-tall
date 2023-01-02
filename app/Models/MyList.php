<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MyList extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'lists';
    protected $guarded = ['id'];

    public static function getList($slug)
    {
        $list = (new static)::query()
            ->where('slug', $slug)
            ->first();
        if ($list) {
            $list['owner'] = User::getUser($list->user_id);
            $list['items'] = Item::query()
                ->where('list_id', $list->id)
                ->get();
        }
        return $list;
    }

    public static function getLists()
    {
        $lists = (new static)::query()
            ->get()
            ->whereNull('deleted_at')
            ->values();
            
        $users = User::getUsers();

        $items = Item::query()
            ->get();

        $lists = $lists
            ->map(function ($list) use ($users, $items) {
                $list['owner'] = $users
                    ->where('id', $list->user_id)
                    ->first();
                $list['items'] = $items
                    ->where('list_id', $list->id)
                    ->values();
                return $list;
            });
        return $lists;
    }
}
