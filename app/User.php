<?php

namespace App;

use App\Filters\UserFilter;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'active' => 'bool',
        'last_login_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function newEloquentBuilder($query)
    {
        return new UserQuery($query);
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class)->withDefault();
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class)->withDefault();
    }

    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getStateAttribute()
    {
        if ($this->active !== null) {
            return $this->active ? 'active' : 'inactive';
        }
    }

    public function setStateAttribute($value)
    {
        $this->attributes['active'] = ($value == 'active');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function delete()
    {

        parent::delete();

        DB::transaction(function () {
            if (parent::delete()) {
                $this->profile()->delete();


                DB::table('skill_user')
                    ->where('user_id', $this->id)
                    ->update(['deleted_at' => now()]);
            }
        });


    }

}
