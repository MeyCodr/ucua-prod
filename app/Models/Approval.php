<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Approval extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "approvals";

    /**
     * Get the ticket that owns the Approval
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    /**
     * Get the approver that owns the Approval
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    /**
     * The people an admin manually assigned to this approval step. When there are any, only
     * they (or an admin) can respond to it.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function assignees()
    {
        return $this->belongsToMany(User::class, 'approval_assignees', 'approval_id', 'user_id')
            ->withTimestamps();
    }

    /**
     * Get the admin who made the manual assignment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by_id');
    }

    /**
     * Get the group that owns the Approval
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}
