<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (User $user) {
            $user->password = Hash::make($user->password);
        });

        static::updating(function (User $user) {
            if ($user->isDirty(['password'])) {
                $user->password = Hash::make($user->password);
            }
        });
    }

    public static function getUser($id) {
        $user = (new static)::where('id', $id)->first();
        $user['profile_url'] = Attachment::query()
            ->where('kind', 'USER')
            ->where('parent_id', $user->id)
            ->first();
        return $user;
    }

    public static function getUsers() {
        $users = (new static)::get();
        $attachments = Attachment::query()
            ->get();
        $users = collect($users)->map(function ($user) use ($attachments) {
            $user['profile_url'] = $attachments
                ->where('kind', 'USER')
                ->where('parent_id', $user->id)
                ->first();
            return $user;
        });
        return $users;
    }
}
