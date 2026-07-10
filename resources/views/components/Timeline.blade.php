@php
    $level3Attachments = $ticket->attachment->where('level', 3);
    $hasLevel3Attachments = $level3Attachments->count() > 0;

    // The level-1 approval's group_id is a static default (always "hodept"), not the
    // specific people this ticket was actually routed to. Resolve the real PICs for
    // this ticket instead of listing every member of that group.
    $level1Pics = collect([
        optional($ticket->dep_responsible)->head_department,
        optional($ticket->sub_dep_responsible)->head_subdepartment,
        optional($ticket->plant_involve)->head_plant,
        optional(optional($ticket->dep_responsible)->division)->head_div,
    ])->filter()->unique('id')->values();
@endphp

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <div class="grid md:grid-cols-3 gap-x-5">
            <div class="col-span-2">
                <div class="timeline">
                    @for ($i = 1; $i <= 4; $i++)
                        @if ($ticket->approval->where('approver_level', $i)->count() > 0)
                            <div class="container right">
                                <div class="content">
                                    <div class="row">
                                        <div class="col-md-12">
                                            @forelse ($ticket->approval->where('approver_level', $i) as $each)
                                                @php
                                                    $approverUsers = $i == 1 ? $level1Pics : $each->group->users;
                                                @endphp
                                                <div class="status-box">
                                                    <div class="lbl1">
                                                        {{ $each->group->name_display }}
                                                    </div>
                                                    <div>
                                                        <div style="font-weight:500;">
                                                            @forelse ($approverUsers as $item)
                                                                {{ $item->name }}, {{ $item->email }} <br>
                                                            @empty
                                                                <span class="text-gray-500">No head assigned</span>
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                    <hr style="margin:10px 0 10px 0;">
                                                    <div style="text-align:right;">
                                                        @if ($each->approver_status == 'Verified' || $each->approver_status == 'Completed')
                                                            <div
                                                                class="inline-block bg-green-200 rounded-full py-1 px-3">
                                                                {{ $each->approver_status }}</div>
                                                        @elseif($each->approver_status == 'Declined')
                                                            <div class="inline-block bg-red-200 rounded-full py-1 px-3">
                                                                {{ $each->approver_status }}</div>
                                                        @elseif($each->approver_status == 'Disabled')
                                                            <div class="box5 inline-block">
                                                                {{ $each->approver_status }}
                                                            </div>
                                                        @else
                                                            <div
                                                                class="inline-block bg-yellow-200 rounded-full py-1 px-3">
                                                                {{ $each->approver_status }}</div>
                                                        @endif
                                                    </div>
                                                    <div style="text-align:right;">
                                                        <div class="datetime">On
                                                            {{ \Carbon\Carbon::parse($each->respond_at)->format('d/m/Y, g:i A') }}
                                                        </div>
                                                    </div>
                                                    <div class="remark-wrap remark-wrap-remark_{{ $each->id }}">
                                                        <div class="lbl1">Remark:</div>
                                                        <div>
                                                            {{ $each->approver_remark }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endfor
                </div>
            </div>

            @if ($hasLevel3Attachments)
                <div class="col-span-1">
                    <div class="bg-white shadow-sm sm:rounded-lg p-6 border border-gray-200 w-fit">
                        @component('components.Carousel', [
                            'attachments' => $level3Attachments,
                            'ticket' => $ticket,
                            'approverLevel' => 0,
                        ])
                        @endcomponent
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
<style>
    /* * {
  box-sizing: border-box;
} */

    /* Set a background color */
    /* body {
  background-color: #474e5d;
  font-family: Helvetica, sans-serif;
} */

    /* The actual timeline (the vertical ruler) */
    .timeline {
        position: relative;
        max-width: 1200px;
        margin: 0;
    }

    /* The actual timeline (the vertical ruler) */
    .timeline::after {
        content: '';
        position: absolute;
        width: 6px;
        background-color: darkgrey;
        top: 0;
        bottom: 0;
        left: 2.2%;
        margin-left: -3px;
    }

    /* Container around content */
    .container {
        padding: 10px 40px;
        position: relative;
        background-color: inherit;
        /* background-color: #FBFCFC; */
        /* background-color: green; */
        /* width: 50%; */
    }

    /* The circles on the timeline */
    .container::after {
        content: '';
        position: absolute;
        width: 25px;
        height: 25px;
        right: -11px;
        background-color: white;
        border: 4px solid #053b73;
        top: 15px;
        border-radius: 50%;
        z-index: 1;
    }

    /* Place the container to the left */
    .left {
        left: 0;
    }

    /* Place the container to the right */
    .right {
        left: 20px;
    }

    /* Add arrows to the left container (pointing right) */
    .left::before {
        content: " ";
        height: 0;
        position: absolute;
        top: 22px;
        width: 0;
        z-index: 1;
        right: 30px;
        border: medium solid white;
        border-width: 10px 0 10px 10px;
        border-color: transparent transparent transparent white;
    }

    /* Add arrows to the right container (pointing left) */
    .right::before {
        content: " ";
        height: 0;
        position: absolute;
        top: 22px;
        width: 0;
        z-index: 1;
        left: 30px;
        border: medium solid white;
        border-width: 10px 10px 10px 0;
        border-color: transparent white transparent transparent;
    }

    /* Fix the circle for containers on the right side */
    .right::after {
        left: -7px;
    }

    /* The actual content */
    .content {
        padding: 10px 15px;
        background-color: #f7f7f7;
        position: relative;
        border-radius: 6px;
    }

    /* Media queries - Responsive timeline on screens less than 600px wide */
    @media screen and (max-width: 600px) {

        /* Place the timelime to the left */
        .timeline::after {
            left: 31px;
        }

        /* Full-width containers */
        .container {
            width: 100%;
            padding-left: 70px;
            padding-right: 25px;
        }

        /* Make sure that all arrows are pointing leftwards */
        .container::before {
            left: 60px;
            border: medium solid white;
            border-width: 10px 10px 10px 0;
            border-color: transparent white transparent transparent;
        }

        /* Make sure all circles are at the same spot */
        .left::after,
        .right::after {
            left: 15px;
        }

        /* Make all right containers behave like the left ones */
        .right {
            left: 0%;
        }
    }
</style>
