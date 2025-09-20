<x-app-layout>
    <x-slot name="title">Sample Collection</x-slot>
    <div>
        <div class="p-2">
            <!-- Add Sample Modal -->
            <x-modal title="Initiate Collection">
                <x-slot name="trigger">
                    <button type="button" @click="open = true"
                        class="px-2 py-1 text-sm rounded bg-green-500 text-white hover:bg-green-600">
                        + Initiate Sample Collection
                    </button>
                </x-slot>
                <small>Select Client or Enter Client name manually</small>

                <form method="POST" action="{{ route('sampling.store') }}" class="text-black w-full"
                    data-turbo-frame="_top" enctype="multipart/form-data"
                    onsubmit="return confirm('Are you sure you want to submit sample collection?');">
                    @csrf
                    <x-form-group>
                        <x-slot name="label">Laboratory Client No</x-slot>
                        <x-slot name="value">
                            <x-select name="client_no" id="client_no" class="tom-selects">
                                <option value="">--select--</option>
                                @foreach($LabClients as $client)
                                    <option value="{{ $client->Client_No }}" data-name="{{ $client->client_name }}">
                                        {{ $client->Client_Name }}</option>
                                @endforeach
                            </x-select>
                        </x-slot>
                    </x-form-group>
                    <x-form-group>
                        <x-slot name="label">Laboratory Client Name</x-slot>
                        <x-slot name="value">
                            <x-input type="text" name="client_name" id="client_name" required />
                        </x-slot>
                    </x-form-group>

                    {{-- Sample Type + Sample Code --}}
                    <x-form-group>
                        <x-slot name="label">Sample Type</x-slot>
                        <x-slot name="value">
                            <x-select name="sample_type" id="sample_type">
                                <option value="">--select--</option>
                                @foreach($AnalysisGroups as $type)
                                    <option value="{{ $type->Analysis_Code }}">{{ $type->Analysis_Group_Name }}</option>
                                @endforeach
                            </x-select>
                        </x-slot>
                    </x-form-group>
                    <x-form-group>
                        <x-slot name="label">Sample Code</x-slot>
                        <x-slot name="value">
                            <x-select name="sample_code" id="sample_code">
                                <option value="">--select--</option>
                                @foreach($AnalysisGroups as $type)
                                    <option value="{{ $type->Analysis_Code }}">{{ $type->Analysis_Group_Name }}</option>
                                @endforeach
                            </x-select>
                        </x-slot>
                    </x-form-group>
                    <div class="flex justify-end mt-4 gap-2">
                        <button @click="open = false" class="px-3 py-1 rounded bg-gray-300 hover:bg-gray-400">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-1 rounded bg-green-600 text-white hover:bg-green-700">
                            Save
                        </button>
                    </div>
                </form>
            </x-modal>
        </div>
        <x-table.table>
            <x-slot name="thead">
                <x-table.th>Collection No.</x-table.th>
                <x-table.th>Client Name</x-table.th>
                <x-table.th>Sampling date</x-table.th>
                <x-table.th>Sampling Time</x-table.th>
                <x-table.th>Sample Type</x-table.th>
                <x-table.th>Sample Code</x-table.th>
                <x-table.th>Status</x-table.th>
            </x-slot>
            <x-slot name="tbody">
                @if($SampleCollections != null && count($SampleCollections) > 0)
                    @foreach($SampleCollections as $SampleCollection)
                        <x-table.tr isEven="{{$loop->even}}"
                            onClick="location = '/staff/sampling/show/{{$SampleCollection->Sample_Collection_No}}'">
                            <x-table.td>{{$SampleCollection->Sample_Collection_No}}</x-table.td>
                            <x-table.td>{{$SampleCollection->Client_Name}}</x-table.td>
                            <x-table.td>{{$SampleCollection->Sampling_Date}}</x-table.td>
                            <x-table.td>{{$SampleCollection->Sampling_Time}}</x-table.td>
                            <x-table.td>{{$SampleCollection->Sampling_Type}}</x-table.td>
                            <x-table.td>{{$SampleCollection->Analysis_Category_No}}</x-table.td>
                            <x-table.td>{{$SampleCollection->Status}}</x-table.td>
                            <x-table.td>
                                @if ($SampleCollection->Status == 'Open')
                                    <x-badge :class="'bg-blue-600'">{{$SampleCollection->Status}}</x-badge>
                                @elseif ($SampleCollection->Status == 'Forwarded')
                                    <x-badge class="bg-green-600">Forwarded</x-badge>
                                @endif
                            </x-table.td>
                        </x-table.tr>
                    @endforeach
                @else
                    <tr class="w-full">
                        <td colspan="9" class="text-black text-center pt-4"><em>*** No Sample Collections ***</em></td>
                    </tr>
                @endif
            </x-slot>
        </x-table.table>
    </div>
    @push('scripts')
        <script>
            new TomSelect("#client_no", {
                create: true,
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });
            new TomSelect("#sample_type", {
                create: true,
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });
            new TomSelect("#sample_code", {
                create: true,
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });  
        </script>
        <script>
            // function fetchClientName() {
            //     const select = document.getElementById('client_no');
            //     const selectedOption = select.options[select.selectedIndex];
            //     const clientName = selectedOption.getAttribute('data-name') || '';

            //     document.getElementById('client_name').value = clientName;
            // }
            $(document).ready(function () {
                $('#client_no').on('change', function () {
                    let clientName = $(this).find(':selected').data('name') || '';
                    $('#client_name').val(clientName);
                });
            });


        </script>
    @endpush
</x-app-layout>