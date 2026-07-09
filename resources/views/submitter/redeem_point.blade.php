<x-layouts.guest2-layout>
    <x-slot name="header">
        <div class="grid md:grid-cols-2">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Point Redemption
                </h2>
            </div>

            <div align="right">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded"
                        onclick="handleClickActionButton(true)">Submit Redeem Request</button>
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="flex justify-center mt-3 mb-4">
        <div class="bg-white shadow-sm sm:rounded-lg p-6 border border-gray-200 w-[50%]">
            <div class="text-center">
                <h3 class="text-lg font-semibold mb-2">Your Total Points: {{ $point_total ?? 0 }}</h3>
                <h4 class="text-md font-semibold mb-2">Points in Progress: {{ $point_floating ?? 0 }}</h4>
                <h4 class="text-md font-semibold mb-2">Points Redeemed: {{ $point_redeem ?? 0 }}</h4>
                <h3 class="text-lg font-semibold mb-2">Total Balance Points:
                    {{ $point_balance ?? 0 }}</h3>
            </div>
            <div class="mt-4">
                <h4 class="text-md font-semibold mb-2">Redeemable Points:</h4>
                <ul class="list-disc list-inside">
                    <li>10 points - RM20 Coupon/Voucher</li>
                    <li>50 points - RM100 Coupon/Voucher</li>
                    <li>100 points - RM200 Coupon/Voucher</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="pt-2 pb-1">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 bg-white rounded divide-x divide-grey-500 shadow hover:shadow-md">
                @forelse ($tabs as $item)
                    <a href="{{ $item->link }}"
                        class="text-center inline-block bg-white rounded p-4 hover:bg-gray-200 hover:text-black @if ($item->isActive) bg-gray-600 text-white @endif">
                        {{ $item->name }}
                    </a>
                @empty
                @endforelse
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col gap-y-1.5">
                @forelse ($redeems as $item)
                    <div class="grid md:grid-cols-3 bg-white rounded p-4 shadow hover:shadow-md hover:bg-gray-200">
                        <div>
                            <div><strong>Point to Redeem</strong></div>
                            <div class="text-lg">{{ $item->points }}</div>
                        </div>
                        <div>
                            <div><strong>Request at</strong></div>
                            <div>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i A') }}</div>
                        </div>
                        <div>
                            <div style="text-align:right;">
                                @if ($item->approver_id != null && $item->respond_at != null)
                                    <div class="inline-block bg-green-200 rounded-full py-1 px-3">
                                        Approved</div>
                                    <div style="text-align:right;">
                                        <div class="datetime">On
                                            {{ \Carbon\Carbon::parse($item->respond_at)->format('d/m/Y, h:i A') }}
                                        </div>
                                    </div>
                                @else
                                    <div class="inline-block bg-yellow-200 rounded-full py-1 px-3">
                                        Pending</div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div>No items</div>
                @endforelse
            </div>
            <div>
                {{ $redeems->links() }}
            </div>
        </div>
    </div>
    @component('submitter.Confirm', [
        'staff_id' => $staff_id,
        'submitter' => $submitter,
        'point_total' => $point_total,
        'point_floating' => $point_floating,
        'point_redeem' => $point_redeem,
        'point_balance' => $point_balance,
    ])
    @endcomponent
</x-layouts.guest2-layout>
