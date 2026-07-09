<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Approval;
use App\Models\TicketAttachment;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "entry_tickets";

    /**
     * Get the plant that owns the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plant()
    {
        return $this->belongsTo(Plant::class, 'site_id');
    }

    /**
     * Get the plant_involve that owns the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plant_involve()
    {
        return $this->belongsTo(Plant::class, 'plant_inv_id');
    }

    /**
     * Get the department that owns the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * Get the subdepartment that owns the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sub_department()
    {
        return $this->belongsTo(SubDepartment::class, 'sub_department_id');
    }

    /**
     * Get the dep_responsible that owns the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dep_responsible()
    {
        return $this->belongsTo(Department::class, 'dept_res_id');
    }

    /**
     * Get the sub_dep_responsible that owns the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sub_dep_responsible()
    {
        return $this->belongsTo(SubDepartment::class, 'sub_dept_res_id');
    }

    /**
     * Get the gm_responsible that owns the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gm_responsible()
    {
        return $this->belongsTo(User::class, 'gm_res_id');
    }

    /**
     * Get the stop_culture that owns the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stop_culture()
    {
        return $this->belongsTo(StopCulture::class, 'stop_cult_id');
    }

    /**
     * Get the zero_harm that owns the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function zero_harm()
    {
        return $this->belongsTo(ZeroHarmRule::class, 'zero_harm_id');
    }

    /**
     * Get the rank that owns the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rank()
    {
        return $this->belongsTo(Rank::class, 'rank_id');
    }

    /**
     * Get the site that owns the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    /**
     * Get the location that owns the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    /**
     * Get all of the attachment for the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachment()
    {
        return $this->hasMany(TicketAttachment::class, 'ticket_id');
    }

    /**
     * Get the unsafe_cond_act that owns the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unsafe_cond_act()
    {
        return $this->belongsTo(Unsafe::class, 'ucua_type');
    }

    /**
     * Get all of the approval for the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function approval()
    {
        return $this->hasMany(Approval::class, 'ticket_id');
    }
}
