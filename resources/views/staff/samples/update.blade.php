<x-app-layout>
    <x-slot name="title">Sample Collection</x-slot>
    <x-panel>

        <x-slot name="title">{{$action == 'create' ? 'New' : 'Edit'}} Sample Collection Form </x-slot>
        <x-slot name="body">
            <form method="POST" action="{{ $action == 'create' ? route('sampling.store') : route('sampling.update')}}"
                class="text-black w-full" data-turbo-frame="_top" enctype="multipart/form-data"
                onsubmit="return confirm('Are you sure you want to submit sample collection?');">
                @csrf
                @if($action == 'edit')
                    @method('PUT')
                    <input id="collectionNo" type="hidden" name="collectionNo"
                        value="{{isset($sampleCollection) ? $sampleCollection->Sample_Collection_No : ''}}" />
                @endif
                <x-grid>
                    <x-grid-col>
                        <x-form-group>
                            <x-slot name="label">Laboratory Client No</x-slot>
                            <x-slot name="value">
                                <x-select name="client_no" id="client_no" class="tom-selects">
                                    <option value="">--select--</option>
                                    @foreach($LabClients as $client)
                                        <option value="{{ $client->Client_No }}" data-name="{{ $client->client_name }}"
                                            {{$action == 'edit' ? $client->Client_No == $SampleCollectionHeader->Client_No ? 'selected' : '' : old('client_no') }}>
                                            {{ $client->Client_Name }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </x-slot>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col>
                        <x-form-group>
                            <x-slot name="label">Laboratory Client Name</x-slot>
                            <x-slot name="value">
                                <x-input type="text" name="client_name" id="client_name"
                                    value="{{ $SampleCollectionHeader->Client_Name }}" required />
                            </x-slot>
                        </x-form-group>
                    </x-grid-col>
                </x-grid>

                {{-- Sample Type + Sample Code --}}
                <x-grid>
                    <x-grid-col>
                        <!-- <x-form-group>
                            <x-slot name="label">Sample Type</x-slot>
                            <x-slot name="value">
                                <x-select name="sample_type" id="sample_type">
                                    <option value="">--select--</option>
                                    @foreach($AnalysisGroups as $type)
                                        <option value="{{ $type->Analysis_Code }}">
                                            {{ $type->Analysis_Group_Name }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </x-slot>
                        </x-form-group>


                        <x-form-group>
                            <x-slot name="label">Sample Code</x-slot>
                            <x-slot name="value">
                                <select id="sample_code" name="sample_code" placeholder="Select Sample Code"></select>
                            </x-slot>
                        </x-form-group> -->
                        <x-form-group>
                            <x-slot name="label">Sample Type</x-slot>
                            <x-slot name="value">
                                <x-select name="sample_type" id="sample_type">
                                    <option value="">--select--</option>
                                    @foreach($AnalysisGroups as $type)
                                        <option value="{{ $type->Analysis_Code }}" {{$action == 'edit' ? $type->Analysis_Code == $SampleCollectionHeader->Analysis_Category_No ? 'selected' : '' : old('sample_type') }}>{{ $type->Analysis_Group_Name }}</option>
                                    @endforeach
                                </x-select>
                            </x-slot>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col>
                        <x-form-group>
                            <x-slot name="label">Sample Code</x-slot>
                            <x-slot name="value">
                                <x-select name="sample_code" id="sample_code">
                                    <option value="">--select--</option>
                                    @foreach($AnalysisService as $service)
                                        <option value="{{ $service->Service_Code }}" {{$action == 'edit' ? $service->Service_Code == $SampleCollectionHeader->Service_Code ? 'selected' : '' : old('sample_code') }}>{{ $service->Service_Name }}</option>
                                    @endforeach
                                </x-select>
                            </x-slot>
                        </x-form-group>
                    </x-grid-col>
                        <x-grid-col>
                            <x-form-group>
                                <x-slot name="label">Samples Collected</x-slot>
                                <x-slot name="value"><x-input disabled value="{{ count($SamplingPoints) }}" /> </x-slot>
                            </x-form-group>
                        </x-grid-col>
                </x-grid>

                {{-- Parameters Section --}}
                <div x-data="{ open: true }" class="mt-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold">Parameter Findings</h3>
                        <button type="button" @click="open = !open"
                            class="px-2 py-1 text-sm rounded bg-green-500 text-white hover:bg-green-600">
                            <span x-show="open">- Hide</span>
                            <span x-show="!open">+ Show</span>
                        </button>
                    </div>

                    <div x-show="open" x-transition class="mt-3">
                        <x-grid>
                            <x-grid-col>
                                <x-form-group>
                                    <x-slot name="label">Weather Conditions</x-slot>
                                    <x-slot name="value"><x-input name="weather_conditions" /></x-slot>
                                </x-form-group>
                            </x-grid-col>

                            <x-grid-col>
                                <x-form-group>
                                    <x-slot name="label">Temperature (°C)</x-slot>
                                    <x-slot name="value"><x-input type="number" step="0.01"
                                            name="temperature" /></x-slot>
                                </x-form-group>
                            </x-grid-col>

                            <x-grid-col>
                                <x-form-group>
                                    <x-slot name="label">Water Body Temperature (°C)</x-slot>
                                    <x-slot name="value"><x-input type="number" step="0.01"
                                            name="water_temperature" /></x-slot>
                                </x-form-group>
                            </x-grid-col>

                            <x-grid-col>
                                <x-form-group>
                                    <x-slot name="label">Sampling Area</x-slot>
                                    <x-slot name="value"><x-input name="sampling_area" /></x-slot>
                                </x-form-group>
                            </x-grid-col>

                            <x-grid-col>
                                <x-form-group>
                                    <x-slot name="label">Sampling Method</x-slot>
                                    <x-slot name="value"><x-input name="sampling_method" /></x-slot>
                                </x-form-group>
                            </x-grid-col>

                            <x-grid-col>
                                <x-form-group>
                                    <x-slot name="label">Sample Type</x-slot>
                                    <x-slot name="value"><x-input name="sampling_type" /></x-slot>
                                </x-form-group>
                            </x-grid-col>

                            <x-grid-col>
                                <x-form-group>
                                    <x-slot name="label">Equipment Used</x-slot>
                                    <x-slot name="value"><x-input name="equipment_used" /></x-slot>
                                </x-form-group>
                            </x-grid-col>

                            <x-grid-col>
                                <x-form-group>
                                    <x-slot name="label">Sampling Depth</x-slot>
                                    <x-slot name="value"><x-input name="sampling_depth" /></x-slot>
                                </x-form-group>
                            </x-grid-col>

                            <x-grid-col>
                                <x-form-group>
                                    <x-slot name="label">Type of Container</x-slot>
                                    <x-slot name="value"><x-input name="type_of_container" /></x-slot>
                                </x-form-group>
                            </x-grid-col>

                            <x-grid-col>
                                <x-form-group>
                                    <x-slot name="label">Preservation Agent Used</x-slot>
                                    <x-slot name="value"><x-input name="preservation_agent" /></x-slot>
                                </x-form-group>
                            </x-grid-col>
                        </x-grid>
                    </div>
                </div>


                <div class="p-2 flex justify-center mt-4">
                    <!-- <x-jet-button class="rounded-full bg-blue-800">Save & Continue</x-jet-button> -->
                    <button type="button" @click="open = !open"
                        class="px-2 py-1 text-sm rounded bg-green-500 text-white hover:bg-green-600">
                        Save Collection Header
                    </button>
                </div>
            </form>
            @if($SamplingPoints && count($SamplingPoints) > 0)
                @include('staff.samples.sampling-points', ['SamplingPoints' => $SamplingPoints])
            @endif
        </x-slot>
    </x-panel>
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
        </script>
        <!-- <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            // Initialize TomSelect for Sample Code
                            const sampleCodeSelect = new TomSelect("#sample_code", {
                                valueField: "code",
                                labelField: "name",
                                searchField: "name",
                                placeholder: "Select Sample Code",
                                load: function (query, callback) {
                                    let type = document.getElementById("sample_type").value;
                                    if (!type) return callback(); // stop if no type selected

                                    fetch(`/sample-codes/${type}?q=${encodeURIComponent(query)}`)
                                        .then(response => response.json())
                                        .then(json => {
                                            callback(json); // expects [{code:"X1", name:"Code X1"}]
                                        })
                                        .catch(() => {
                                            callback();
                                        });
                                },
                            });

                            // Reset Sample Code when Sample Type changes
                            document.getElementById("sample_type").addEventListener("change", function () {
                                sampleCodeSelect.clear();
                                sampleCodeSelect.clearOptions();
                            });
                        });
                    </script> -->
        <script>
            $(document).ready(function () {
                // Get the route with placeholder from Laravel
                let sampleCodesUrl = @json(route('sampling.groupServices', ['type' => '__TYPE__']));

                // Initialize TomSelect for Sample Code
                var sampleCodeSelect = new TomSelect("#sample_code", {
                    valueField: "Service_Code",
                    labelField: "Service_Name",
                    searchField: "Service_Name",
                    placeholder: "Select Sample Code",
                    load: function (query, callback) {
                        var type = $("#sample_type").val();
                        if (!type) return callback();

                        // Replace placeholder with actual type
                        let url = sampleCodesUrl.replace('__TYPE__', type);

                        $.ajax({
                            url: url,
                            data: { q: query },
                            dataType: "json",
                            success: function (res) {
                                callback(res);
                            },
                            error: function () {
                                callback();
                            }
                        });
                    }
                });

                // Auto-refresh sample codes when type changes
                $("#sample_type").on("change", function () {
                    sampleCodeSelect.clear();
                    sampleCodeSelect.clearOptions();

                    let type = $(this).val();
                    if (type) {
                        let url = sampleCodesUrl.replace('__TYPE__', type);
                        $.ajax({
                            url: url,
                            dataType: "json",
                            success: function (res) {
                                sampleCodeSelect.addOptions(res);
                            }
                        });
                    }
                });
            });
        </script>

        <script>
            $(document).ready(function () {
                $('#client_no').on('change', function () {
                    let clientName = $(this).find(':selected').data('name') || '';
                    $('#client_name').val(clientName);
                });

                
            });


        </script>
    @endpush
</x-app-layout>