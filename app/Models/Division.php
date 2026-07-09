<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Division extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Get all of the department for the Division
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function department()
    {
        return $this->hasMany(Department::class, 'division_id');
    }

    /**
     * Get the head_div associated with the Division
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function head_div()
    {
        return $this->belongsTo(User::class, 'user_head_id');
    }
}
