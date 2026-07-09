<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Company;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'department',
        'designation',
        'is_enabled',
        'is_locked',
        'num_failed_login_attempt',
        'last_password_reset',
        'password_expiry_date'
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

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The groups that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'users_groups', 'user_id', 'group_id');
    }

    /**
     * The department that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function department()
    {
        return $this->belongsToMany(Department::class, 'users_departments', 'user_id', 'department_id');
    }

    public static function isAdmin()
    {
        $group = Group::where('name', 'admin')->first();

        if (!$group || !$group->users) {
            return false;
        }

        return $group->users->contains('id', Auth::id());
    }

    public function she_admin()
    {
        $group = Group::where('name', 'she_admin')->first();

        if (!$group || !$group->users) {
            return false;
        }

        return $group->users->contains('id', Auth::id());
    }

    public function hodiv()
    {
        $group = Group::where('name', 'hodiv')->first();

        if (!$group || !$group->users) {
            return false;
        }

        return $group->users->contains('id', Auth::id());
    }

    public function hodept()
    {
        $group = Group::where('name', 'hodept')->first();

        if (!$group || !$group->users) {
            return false;
        }

        return $group->users->contains('id', Auth::id());
    }

    public function hop()
    {
        $group = Group::where('name', 'hop')->first();

        if (!$group || !$group->users) {
            return false;
        }

        return $group->users->contains('id', Auth::id());
    }

    public function hos()
    {
        $group = Group::where('name', 'hos')->first();

        if (!$group || !$group->users) {
            return false;
        }

        return $group->users->contains('id', Auth::id());
    }
}
