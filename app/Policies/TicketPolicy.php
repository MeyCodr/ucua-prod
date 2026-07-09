<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Ticket;
use Illuminate\Auth\Access\Response;

class TicketPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function View(User $user, Ticket $ticket)
    {
        $pic = $ticket->branch->pic_branch;

        return $pic->contains('id',$user->id);
    }
}
