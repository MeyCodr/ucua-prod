<x-app-layout>
    <x-slot name="header">
        <div class="grid md:grid-cols-2">
            <div class="flex justify-start">
                <div class="inline-block mr-20">
                    <a href="{{ route('Division.index') }}">Back</a>
                </div>
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Department #{{ $department->id }}
                    </h2>
                </div>
            </div>
            <div class="text-right">
                <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded"
                    href="{{ route('Department.edit', ['div_id' => $department->division_id, 'Department' => $department->id]) }}">Edit</a>
            </div>
        </div>
    </x-slot>

    {{-- Alert message pop up --}}
    @if (session('alertColor'))
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 my-2">
            @component('components.Alert1')
                @slot('alertColor')
                    {{ session('alertColor') }}
                @endslot
                @slot('message')
                    {{ session('message') }}
                @endslot
            @endcomponent
        </div>
    @endif

    <div class="py-6">
        {{-- Main Info --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-2">
            {{-- General Detail --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="pb-4 font-bold">
                        General
                    </div>
                    <div class="grid md:grid-cols-4 gap-4">
                        <div>
                            <div>
                                Name
                            </div>
                            <div>
                                {{ $department->name }}
                            </div>
                        </div>

                        <div>
                            <div>
                                Short Name
                            </div>
                            <div>
                                {{ $department->short_name }}
                            </div>
                        </div>

                        <div>
                            <div>
                                Head of Division
                            </div>
                            <div>
                                @if ($department->head_department)
                                    <ul class="list-disc">
                                        <li>{{ $department->head_department->name }}</li>
                                        <li>{{ $department->head_department->email }}</li>
                                    </ul>
                                @else
                                    <span class="italic">Not available</span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <div>
                                Delete Department

                            </div>
                            <div>
                                <button type="button"
                                    class="bg-red-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded"
                                    onclick="handleClickDelAccButton(true)">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($department->have_sub_department > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-2">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="grid md:grid-cols-2">
                            <div class="flex justify-start">
                                <div class="pb-4 font-bold">
                                    Sub Department list
                                </div>
                            </div>
                            <div class="text-right">
                                <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded"
                                    href="{{ route('SubDepartment.create', ['div_id' => $department->division_id, 'dept_id' => $department->id]) }}">Create
                                    Sub Department</a>
                            </div>
                        </div>

                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            <div class="flex flex-col gap-y-1.5">
                                @forelse ($department->subdepartment as $item)
                                    <a
                                        href="{{ route('SubDepartment.show', ['div_id' => $department->division_id, 'dept_id' => $department->id, 'SubDepartment' => $item->id]) }}">
                                        <div
                                            class="grid md:grid-cols-3 bg-white rounded p-3 shadow hover:shadow-md hover:bg-gray-200 text-black">
                                            <div>
                                                <div class="font-semibold">ID #{{ $item->id }}</div>
                                            </div>
                                            <div>
                                                <div class="text-gray-500">Name</div>
                                                <div>{{ $item->name }}</div>
                                            </div>
                                            <div>
                                                <div class="text-gray-500">Head of Sub Department</div>
                                                @if ($item->head_department)
                                                    <div>{{ $item->head_department->name }}</div>
                                                @else
                                                    <div>No Head</div>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div>No items</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($department->have_plant > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-2">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="grid md:grid-cols-2">
                            <div class="flex justify-start">
                                <div class="pb-4 font-bold">
                                    Plant list
                                </div>
                            </div>
                            <div class="text-right">
                                <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded"
                                    href="{{ route('Plant.create', ['div_id' => $department->division_id, 'dept_id' => $department->id]) }}">Create
                                    Plant</a>
                            </div>
                        </div>
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            <div class="flex flex-col gap-y-1.5">
                                @forelse ($department->plant as $item)
                                    <a
                                        href="{{ route('Plant.show', ['div_id' => $department->division_id, 'dept_id' => $department->id, 'Plant' => $item->id]) }}">
                                        <div
                                            class="grid md:grid-cols-3 bg-white rounded p-3 shadow hover:shadow-md hover:bg-gray-200 text-black">
                                            <div>
                                                <div class="font-semibold">ID #{{ $item->id }}</div>
                                            </div>
                                            <div>
                                                <div class="text-gray-500">Name</div>
                                                <div>{{ $item->name }}</div>
                                            </div>
                                            <div>
                                                <div class="text-gray-500">Head of Plant</div>
                                                @if ($item->head_plant)
                                                    <div>{{ $item->head_plant->name }}</div>
                                                @else
                                                    <div>No Head</div>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div>No items</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script src="{{ asset('jquery/3.4.1/jquery.min.js') }}"></script>
    <script>
        function handleClickDelAccButton(status) {
            if (status) {
                $('.modalDeleteAccLink').show();
            } else {
                $('.modalDeleteAccLink').hide();
            }

        }
    </script>

    <div class="fixed z-10 inset-0 overflow-y-auto modalDeleteAccLink hidden" role="dialog">
        <div class="flex items-end justify-center min-h-screen pt-1 px-4 pb-20 text-center sm:block sm:p-0">

            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form
                    action="{{ route('Department.destroy', ['div_id' => $department->division_id, 'Department' => $department->id]) }}"
                    method="post" enctype="multipart/form-data">
                    @csrf
                    @method('DELETE')
                    <div class="bg-white px-4 pt-3 pb-4 sm:p-6 sm:pb-4">
                        <div class="">
                            <div class="mt-1 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 font-bold" id="modal-title">
                                    Confirmation
                                </h3>
                                <div class="mt-2">
                                    <p class="text-gray-500">
                                        You are about to delete this department. Continue?
                                    </p>
                                </div>
                                <div class="mt-2">
                                    <input type="text" name="department_id" value="{{ $department->id }}" hidden>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Continue
                        </button>
                        <button type="button"
                            class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                            onclick="handleClickDelAccButton(false)">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
