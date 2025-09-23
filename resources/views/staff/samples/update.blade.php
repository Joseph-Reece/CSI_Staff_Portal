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
                                <x-select name="client_no" id="client_no">
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

                <div class="p-2 flex justify-center mt-4">
                    <!-- <x-jet-button class="rounded-full bg-blue-800">Save & Continue</x-jet-button> -->
                    <button type="button" @click="open = !open"
                        class="px-2 py-1 text-sm rounded bg-green-500 text-white hover:bg-green-600">
                        Save Collection Header
                    </button>
                </div>
            </form>
            {{-- Parameter Findings Section --}}
            <section class="mb-5">
                <form id="parameters_form">
                    @csrf
                    @method('PUT')
                    <input id="collectionNo2" type="hidden" name="collectionNos"
                        value="{{isset($SampleCollectionHeader) ? $SampleCollectionHeader->Sample_Collection_No : ''}}" />
                    {{-- Parameters Section --}}
                    <div x-data=" { open: true }" class="mt-4">
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
                                        <x-slot name="value"><x-input name="weather_conditions"
                                                value="{{ $SampleCollectionHeader->Weather_Conditions }}" /></x-slot>
                                    </x-form-group>
                                </x-grid-col>

                                <x-grid-col>
                                    <x-form-group>
                                        <x-slot name="label">Temperature (°C)</x-slot>
                                        <x-slot name="value"><x-input type="number" step="0.01" name="temperature"
                                                value="{{ $SampleCollectionHeader->Temperature }}" /></x-slot>
                                    </x-form-group>
                                </x-grid-col>

                                <x-grid-col>
                                    <x-form-group>
                                        <x-slot name="label">Water Body Temperature (°C)</x-slot>
                                        <x-slot name="value"><x-input type="number" step="0.01" name="water_temperature"
                                                value="{{ $SampleCollectionHeader->Water_Body_Temperature }}" /></x-slot>
                                    </x-form-group>
                                </x-grid-col>

                                <x-grid-col>
                                    <x-form-group>
                                        <x-slot name="label">Sampling Area Condition</x-slot>
                                        <x-slot name="value"><x-input name="sampling_area"
                                                value="{{ $SampleCollectionHeader->Sampling_Area_Condition }}" /></x-slot>
                                    </x-form-group>
                                </x-grid-col>

                                <x-grid-col>
                                    <x-form-group>
                                        <x-slot name="label">Sampling Method</x-slot>
                                        <x-slot name="value"><x-input name="sampling_method"
                                                value="{{ $SampleCollectionHeader->Sampling_Method }}" /></x-slot>
                                    </x-form-group>
                                </x-grid-col>

                                <x-grid-col>
                                    <x-form-group>
                                        <x-slot name="label">Type of Sample</x-slot>
                                        <x-slot name="value">
                                            <x-select name="type_of_sample" id="type_of_sample">
                                                <option>--select--</option>
                                                <option value="1" {{ (old('type_of_sample') ?? ($action == 'edit' ? $SampleCollectionHeader->Type_of_Sample : null)) == "Snap" ? 'selected' : '' }}>
                                                    Snap
                                                </option>
                                                <option value="2" {{ (old('type_of_sample') ?? ($action == 'edit' ? $SampleCollectionHeader->Type_of_Sample : null)) == "Depth Profile" ? 'selected' : '' }}>
                                                    Depth Profile
                                                </option>
                                                <option value="3" {{ (old('type_of_sample') ?? ($action == 'edit' ? $SampleCollectionHeader->Type_of_Sample : null)) == "Area Profile" ? 'selected' : '' }}>
                                                    Area Profile
                                                </option>
                                            </x-select>
                                        </x-slot>
                                    </x-form-group>
                                </x-grid-col>

                                <x-grid-col>
                                    <x-form-group>
                                        <x-slot name="label">Sampling Depth</x-slot>
                                        <x-slot name="value"><x-input name="sampling_depth"
                                                value="{{ $SampleCollectionHeader->Sampling_depth }}" /></x-slot>
                                    </x-form-group>
                                </x-grid-col>

                                <x-grid-col>
                                    <x-form-group>
                                        <x-slot name="label">Equipment Used</x-slot>
                                        <x-slot name="value"><x-input name="equipment_used"
                                                value="{{ $SampleCollectionHeader->Equipment_Used }}" /></x-slot>
                                    </x-form-group>
                                </x-grid-col>


                                <x-grid-col>
                                    <x-form-group>
                                        <x-slot name="label">Type of Container</x-slot>
                                        <x-slot name="value"><x-input name="container_type"
                                                value="{{ $SampleCollectionHeader->Type_of_Container }}" /></x-slot>
                                    </x-form-group>
                                </x-grid-col>

                                <x-grid-col>
                                    <x-form-group>
                                        <x-slot name="label">Preservation Agent Used</x-slot>
                                        <x-slot name="value"><x-input name="preservation_agent"
                                                value="{{ $SampleCollectionHeader->Preservation_Agent_Used }}" /></x-slot>
                                    </x-form-group>
                                </x-grid-col>
                                <x-grid-col>
                                    <x-form-group>
                                        <x-slot name="label">Activities During Sampling </x-slot>
                                        <x-slot name="value">
                                            <textarea name="activities"
                                                cols="40">{{ $SampleCollectionHeader->Activities_during_Sampling }}</textarea>
                                        </x-slot>
                                    </x-form-group>
                                </x-grid-col>
                            </x-grid>
                            <div class="p-2 flex justify-center mt-4">
                                <button type="submit" id="saveParametersBtn"
                                    class="px-2 py-1 text-sm rounded bg-green-500 text-white hover:bg-green-600">
                                    Save parameter findings
                                </button>
                            </div>
                        </div>
                    </div>


                </form>
            </section>


            {{-- Sampling Points Section --}}
            @include('staff.samples.sampling-points', ['SamplingPoints' => $SamplingPoints])
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
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
                // Handle Save button click
                $('#parameters_form').on('submit', function (e) {
                    e.preventDefault();

                    // Get form data
                    let formData = {
                        weather_conditions: $('input[name="weather_conditions"]').val(),
                        temperature: parseFloat($('input[name="temperature"]').val()),
                        water_temperature: parseFloat($('input[name="water_temperature"]').val()),
                        sampling_area: $('input[name="sampling_area"]').val(),
                        sampling_method: $('input[name="sampling_method"]').val(),
                        type_of_sample: $('select[name="type_of_sample"]').val(),
                        sampling_depth: $('input[name="sampling_depth"]').val(),
                        equipment_used: $('input[name="equipment_used"]').val(),
                        ContainerType: $('input[name="container_type"]').val(),
                        preservation_agent: $('input[name="preservation_agent"]').val(),
                        activities: $('textarea[name="activities"]').val(),
                    };

                    // Get the collection ID from the URL or a hidden input
                    let collectionId = "{{ $SampleCollectionHeader->Sample_Collection_No }}"
                    let url = "{{ route('sampling.updateparameters', ':id') }}".replace(':id', collectionId);

                    // AJAX request
                    $.ajax({
                        url: url,
                        type: 'PUT',
                        data: formData,
                        dataType: 'json',
                        beforeSend: function () {
                            // Disable submit button and show loading state
                            $('button[id="saveParametersBtn"]').prop('disabled', true).text('Saving...');
                        },
                        success: function (response) {
                            // Handle success
                            alert('Parameter findings saved successfully!');
                            // Optionally reset form or redirect
                            // $('#parameters_form')[0].reset();
                        },
                        error: function (xhr) {
                            // Handle errors
                            let errors = xhr.responseJSON?.errors;
                            if (errors) {
                                let errorMessage = 'Validation errors:\n';
                                $.each(errors, function (key, value) {
                                    errorMessage += value + '\n';
                                });
                                alert(errorMessage);
                            } else {
                                alert('An error occurred while saving. Please try again.');
                            }
                        },
                        complete: function () {
                            // Re-enable submit button
                            $('button[id="saveParametersBtn"]').prop('disabled', false).text('Save parameter findings');
                        }
                    });
                });

                // Attach click event to the save button
                $('button[id="saveParametersBtn"]').on('click', function () {
                    console.log('hereeee');
                    $('#parameters_form').trigger('submit');
                });

                $('#sampling_point_form').on('submit', function (e) {
                    e.preventDefault();

                    // let url = $(this).data('url');
                    let collectionId = "{{ $SampleCollectionHeader->Sample_Collection_No }}"
                    let url = "{{ route('sampling.samplingpoints', ':id') }}".replace(':id', collectionId);

                    let formData = {
                        analysis_category: $('input[name="analysis_category"]').val(),
                        service_code: $('input[name="service_code"]').val(),
                        point_name: $('input[name="point_name"]').val(),
                        sample_collected: $('input[name="sample_collected"]').val(),
                        reason: $('input[name="reason"]').val(),
                        analysis_category: "{{$SampleCollectionHeader->Analysis_Category_No}}",
                        service_code: "{{$SampleCollectionHeader->Service_Code}}",
                    };

                    $.ajax({
                        url: url,
                        type: 'PUT',
                        data: formData,
                        success: function (res) {
                            alert(res.message || "Sampling point saved!");
                            $('#sampling_point_form')[0].reset();
                            // Close modal
                            $('.modal [x-data]').attr('open', false);

                            // Reload table section dynamically
                            $('#sampling-points-container').html(res.html);
                        },
                        error: function (xhr) {
                            let errors = xhr.responseJSON?.errors;
                            if (errors) {
                                let errorMessage = 'Validation errors:\n';
                                $.each(errors, function (key, value) {
                                    errorMessage += value + '\n';
                                });
                                alert(errorMessage);
                            } else {
                                alert('An error occurred while saving. Please try again.');
                            }
                        }
                    });
                });
                $('button[id="saveSamplingPointBtn"]').on('click', function () {
                    console.log('hereeee');
                    $('#sampling_point_form').trigger('submit');
                });

                $('button[id="deletesampling"').on('click', function () {
                    let url = $(this).data('url');
                    let record = $(this).data('line');

                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: { 
                            _token: '{{ csrf_token() }}' ,
                            CollectionNo: "{{ $SampleCollectionHeader->Sample_Collection_No }}"
                        },
                        success: function (res) {
                            if (res.return_value == true) {
                                $('#row-' + record).remove();

                                // Close delete modal
                                $('#deletepointmodal').addClass('hidden');
                            }
                            if (res.return_value == false) {
                                alert('Something Went Wrong');
                            }

                        },
                        error: function (xhr) {
                            let errors = xhr.responseJSON?.errors;
                            console.log(errors)
                            if (errors) {
                                let errorMessage = 'Validation errors:\n';
                                $.each(errors, function (key, value) {
                                    errorMessage += value + '\n';
                                });
                                alert(errorMessage);
                            } else {
                                alert('An error occurred while saving. Please try again.');
                            }
                        }
                    });
                });


            });
        </script>

    @endpush
</x-app-layout>