<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plant extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Get the head_plant associated with the Plant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function head_plant()
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
