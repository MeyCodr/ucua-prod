<x-layouts.guest2-layout>
    <x-slot name="header">
        <div class="grid md:grid-cols-2">
            <div class="flex justify-start">
                <div class="inline-block mr-20">
                    <a href="{{ route('admin.redeem.list', ['status' => $status]) }}">Back</a>
                </div>
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        ID #{{ $redeem->id }} <span
                            class="inline-block @if ($status == 'Pending') bg-yellow-200 @else bg-green-200 @endif rounded-full py-1 px-3">{{ $status }}</span>
                    </h2>
                </div>
            </div>
            <div class="text-right">
                <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded"
                    onclick="handleClickActionButton(true, 'Approve')">Approve</button>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 md:mb-5">
            {{-- Alert message pop up --}}
            @if (session('alertColor'))
                @component('components.Alert')
                    @slot('alertColor')
                        {{ session('alertColor') }}
                    @endslot
                    @slot('message')
                        {{ session('message') }}
                    @endslot
                @endcomponent
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col gap-y-8">
                        <div class="grid md:grid-cols-2 gap-x-3">
                            <div>
                                <div class="text-gray-600">Staff ID</div>
                                <div><strong>{{ $redeem->staff_id }}</strong></div>
                            </div>
                            <div>
                                <div class="text-gray-600">Point to Redeem</div>
                                <div class="text-red">
                                    <strong>{{ $redeem->points }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="grid md:grid-cols-2 gap-x-3">
                            <div>
                                <div class="text-gray-600">Requested by</div>
                                <div><strong>{{ $submitter->name }}</strong></div>
                                <div><strong>{{ $submitter->email }}</strong></div>
                                <div>{{ $submitter->phone_number }}</div>
                            </div>
                            <div>
                                <div class="text-gray-600">Requested on</div>
                                <div>
                                    {{ \Carbon\Carbon::parse($redeem->created_at)->format('d/m/Y, g:i A') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pb-3 pt-8">
                <h4 class="text-xl">Approval</h4>
            </div>
            <div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="grid md:grid-cols-1 gap-x-5">
                            <div class="timeline">
                                <div class="container right">
                                    <div class="content">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="status-box">
                                                    <div class="lbl1">
                                                        SHE ADMIN PHN
                                                        @if ($redeem->approver_id != null && $redeem->respond_at != null)
                                                            <div>
                                                                By
                                                                {{ $redeem->approver->name }} ({{ $approver->email }})
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <hr style="margin:10px 0 10px 0;">
                                                    <div style="text-align:right;">
                                                        @if ($redeem->approver_id != null && $redeem->respond_at != null)
                                                            <div
                                                                class="inline-block bg-green-200 rounded-full py-1 px-3">
                                                                Approved
                                                            </div>
                                                        @else
                                                            <div
                                                                class="inline-block bg-yellow-200 rounded-full py-1 px-3">
                                                                Pending
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div style="text-align:right;">
                                                    @if ($redeem->approver_id != null && $redeem->respond_at != null)
                                                        <div>
                                                            By
                                                            {{ $approver->name }} ({{ $approver->email }})
                                                        </div>
                                                    @endif
                                                    <div class="datetime">On
                                                        {{ \Carbon\Carbon::parse($redeem->respond_at)->format('d/m/Y, g:i A') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @component('point.Confirm', [
        'status' => $status,
        'redeem' => $redeem,
        'staff_id' => $redeem->staff_id,
        'submitter' => $submitter,
    ])
    @endcomponent
    <style>
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
</x-layouts.guest2-layout>
