<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubDepartment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The user_subdepartment that belong to the SubDepartment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function user_subdepartment()
    {
        return $this->belongsToMany(User::class, 'user_subdepartment', 'sub_department_id', 'user_id');
    }

    /**
     * Get the head_subdepartment associated with the SubDepartment
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function head_subdepartment()
    {
        return $this->belongsTo(User::class, 'user_head_id');
    }

    /**
     * Get the department that owns the Plant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
