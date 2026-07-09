<x-app-layout>
    <x-slot name="header">
        <div class="grid md:grid-cols-2">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Redemption Requests
                </h2>
            </div>
        </div>
    </x-slot>

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
                    <a
                        href="{{ route('admin.redeem.list.staff', ['status' => $status, 'staff_id' => $item->staff_id, 'redeem_id' => $item->id]) }}">
                        <div class="grid md:grid-cols-3 bg-white rounded p-4 shadow hover:shadow-md hover:bg-gray-200">
                            <div>
                                <strong>ID</strong>
                                <div class="text-lg">#{{ $item->id }}</div>
                                <strong>Staff ID</strong>
                                <div class="text-lg">#{{ $item->staff_id }}</div>
                            </div>
                            <div>
                                <div><strong>Point to Redeem</strong></div>
                                <div class="text-lg">{{ $item->points }}</div>
                                <div><strong>Request at</strong></div>
                                <div>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y h:i A') }}</div>
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
                    </a>
                @empty
                    <div>No items</div>
                @endforelse
            </div>
            <div>
                {{ $redeems->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
