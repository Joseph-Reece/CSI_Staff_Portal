<x-app-layout>
    <x-slot name="title">Leave Requests</x-slot>
    <div>
        <div class="p-2">
            <x-abutton href="/staff/leave/create" class="bg-blue-900"><x-heroicon-o-plus/> New Request</x-abutton>
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
                        <x-table.tr isEven="{{$loop->even}}" onClick="location = '/staff/sampling/show/{{$SampleCollection->Sample_Collection_No}}'">
                            <x-table.td>{{$SampleCollection->Sample_Collection_No}}</x-table.td>
                            <x-table.td>{{$SampleCollection->Client_Name}}</x-table.td>
                            <x-table.td>{{$SampleCollection->Sampling_Date}}</x-table.td>
                            <x-table.td >{{$SampleCollection->Sampling_Time}}</x-table.td>
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
</x-app-layout>
