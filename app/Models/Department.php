<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The users that belong to the Department
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function user_department()
    {
        return $this->belongsToMany(User::class, 'users_departments', 'department_id', 'user_id');
    }

    /**
     * Get the head_department associated with the Department
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function head_department()
    {
        return $this->belongsTo(User::class, 'user_head_id');
    }

    /**
     * Get the division that owns the Department
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    /**
     * Get all of the plant for the Department
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function plant()
    {
        return $this->hasMany(Plant::class, 'department_id');
    }

    /**
     * Get all of the subdepartment for the Department
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subdepartment()
    {
        return $this->hasMany(SubDepartment::class, 'department_id');
    }
}
