<section>
    <div class="flex items-center justify-between mb-3">
        <h3 class="text-lg font-bold">Sampling Points</h3>

        <!-- Add Sample Modal -->
        <x-modal title="Add Sampling Point">
            <x-slot name="trigger">
                <button type="button" @click="open = true"
                    class="px-2 py-1 text-sm rounded bg-green-500 text-white hover:bg-green-600">
                    + Add Sample
                </button>
            </x-slot>

            <form class="space-y-4" id="sampling_point_form">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium">Point Name</label>
                    <input type="text" name="point_name" required class="w-full border rounded px-2 py-1" />
                </div>

                <div>
                    <label class="block text-sm font-medium">Samples Collected</label>
                    <input type="text" name="sample_collected" class="w-full border rounded px-2 py-1" />
                </div>

                <div>
                    <label class="block text-sm font-medium">Reason</label>
                    <textarea name="reason" class="w-full border rounded px-2 py-1"></textarea>
                </div>

                <div class="flex justify-end mt-4 gap-2">
                    <button @click="open = false" class="px-3 py-1 rounded bg-gray-300 hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="button" id="saveSamplingPointBtn"
                        class="px-5 py-1 rounded bg-green-600 text-white hover:bg-green-700">
                        Save
                    </button>
                </div>

            </form>
        </x-modal>
    </div>

    <div class="overflow-x-auto">
        <!-- Desktop Table -->
        <x-table.table class="min-w-full hidden md:table">
            <x-slot name="thead">
                <x-table.th>Point Name</x-table.th>
                <x-table.th>Samples Collected</x-table.th>
                <x-table.th>Reason</x-table.th>
                <x-table.th>Lab Reference</x-table.th>
                <x-table.th>Status</x-table.th>
                <x-table.th>Action</x-table.th>
            </x-slot>
            <x-slot name="tbody">
                @if($SamplingPoints != null && count($SamplingPoints) > 0)
                    @foreach($SamplingPoints as $SamplingPoint)
                        <x-table.tr id="row-{{ $SamplingPoint->Line_No }}" isEven="{{$loop->even}}">
                            <x-table.td>{{$SamplingPoint->Point_Name}}</x-table.td>
                            <x-table.td>{{$SamplingPoint->Samples_Collected}}</x-table.td>
                            <x-table.td>{{$SamplingPoint->Reason}}</x-table.td>
                            <x-table.td>{{$SamplingPoint->Lab_Reference_No}}</x-table.td>
                            <x-table.td>{{$SamplingPoint->Status}}</x-table.td>
                            <x-table.td>
                                <div class="p-2 flex justify-between mt-4">
                                    @if ($SamplingPoint->Status == 'open')
                                        <!-- Edit Button -->
                                        <button type="button"
                                            class="flex items-center gap-2 px-3 py-1 text-sm rounded bg-blue-800 text-white hover:bg-blue-900">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536M9 13l6-6 3 3-6 6H9v-3z" />
                                            </svg>
                                            Edit
                                        </button>

                                        <!-- Delete Confirmation Modal -->
                                        <x-modal title="Delete Sampling Point" id="deletepointmodal">
                                            <x-slot name="trigger">
                                                <button type="button" @click="open = true"
                                                    class="flex items-center gap-2 px-3 py-1 text-sm rounded bg-red-600 text-white hover:bg-red-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M4 7h16m-3-4H7m4 0h2" />
                                                    </svg>
                                                    Delete
                                                </button>
                                            </x-slot>

                                            <p class="text-gray-700">Are you sure you want to delete
                                                <strong>{{ $SamplingPoint->Point_Name }}</strong>?
                                            </p>

                                            <div class="flex justify-end mt-4 gap-2">
                                                <button @click="open = false"
                                                    class="px-3 py-1 rounded bg-gray-300 hover:bg-gray-400">
                                                    Cancel
                                                </button>
                                                <button type="button" id="deletesampling" data-url="{{ route('sampling.deletepoint', $SamplingPoint->Line_No) }}" data-line = "{{  $SamplingPoint->Line_No }}"
                                                    class="flex items-center gap-2 px-3 py-1 text-sm rounded bg-red-600 text-white hover:bg-red-700">
                                                    Delete
                                                </button>
                                            </div>
                                        </x-modal>

                                    @endif
                                </div>
                            </x-table.td>
                        </x-table.tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="text-center text-gray-600 pt-4">
                            <em>*** No Sample Collections ***</em>
                        </td>
                    </tr>
                @endif
            </x-slot>
        </x-table.table>

        <!-- Mobile Card Layout -->
        <div class="md:hidden space-y-4 mt-4">
            @if($SamplingPoints != null && count($SamplingPoints) > 0)
                @foreach($SamplingPoints as $SamplingPoint)
                    <div class="bg-white shadow rounded-lg p-4 border">
                        <p><span class="font-bold">Point Name:</span> {{$SamplingPoint->Point_Name}}</p>
                        <p><span class="font-bold">Samples Collected:</span> {{$SamplingPoint->Samples_Collected}}
                        </p>
                        <p><span class="font-bold">Reason:</span> {{$SamplingPoint->Reason}}</p>
                        <p><span class="font-bold">Lab Ref:</span> {{$SamplingPoint->Lab_Reference_No}}</p>
                        <p><span class="font-bold">Status:</span> {{$SamplingPoint->Status}}</p>

                        @if ($SamplingPoint->Status == 'open')
                            <div class="flex gap-2 mt-3">
                                <!-- Edit -->
                                <button type="button"
                                    class="flex items-center gap-2 px-3 py-1 text-sm rounded bg-blue-800 text-white hover:bg-blue-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536M9 13l6-6 3 3-6 6H9v-3z" />
                                    </svg>
                                    Edit
                                </button>

                                <!-- Delete -->
                                <!-- <button type="button"
                                                                                                class="flex items-center gap-2 px-3 py-1 text-sm rounded bg-red-600 text-white hover:bg-red-700">
                                                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                                                                    stroke="currentColor">
                                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M4 7h16m-3-4H7m4 0h2" />
                                                                                                </svg>
                                                                                                Delete
                                                                                            </button>
                                                                                             -->
                                <!-- Delete Confirmation Modal -->
                                <x-modal title="Delete Sampling Point">
                                    <x-slot name="trigger">
                                        <button type="button" @click="open = true"
                                            class="flex items-center gap-2 px-3 py-1 text-sm rounded bg-red-600 text-white hover:bg-red-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M4 7h16m-3-4H7m4 0h2" />
                                            </svg>
                                            Delete
                                        </button>
                                    </x-slot>

                                    <p class="text-gray-700">Are you sure you want to delete
                                        <strong>{{ $SamplingPoint->Point_Name }}</strong>?
                                    </p>

                                    <x-slot name="footer">
                                        <form method="POST" action="{{ route('sampling.store', $SamplingPoint->id) }}">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700">
                                                Yes, Delete
                                            </button>
                                        </form>
                                    </x-slot>
                                </x-modal>

                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <p class="text-center text-gray-600"><em>*** No Sample Collections ***</em></p>
            @endif
        </div>
    </div>



</section>