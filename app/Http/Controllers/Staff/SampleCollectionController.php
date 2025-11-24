<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\AnalysisGroup;
use App\Models\AnalysisService;
use App\Models\LaboratoryClient;
use App\Models\SampleCollection;
use App\Models\SamplingPoint;
use App\Traits\AttachmentTrait;
use App\Traits\WebServicesTrait;
use Illuminate\Http\Request;

class SampleCollectionController extends Controller
{
    use WebServicesTrait;
    use AttachmentTrait;
    public function __construct()
    {
        $this->middleware('isAuth');
        $this->middleware('staff');
    }

    public function index()
    {

        $SampleCollections = $this->odataClient()->from(SampleCollection::wsName())->get();
        $LabClients = $this->odataClient()->from(LaboratoryClient::wsName())->get();
        $AnalysisGroups = $this->odataClient()->from(AnalysisGroup::wsName())->get();
        // $data = SampleCollection::all();
        // $data = ["SampleCollections" => $SamplesCollected];
        // dd("Samples", $data);

        return view('staff.samples.index')->with(compact('SampleCollections', 'LabClients', 'AnalysisGroups'));
    }
    public function create()
    {
        $LabClients = $this->odataClient()->from(LaboratoryClient::wsName())->get();
        $AnalysisGroups = $this->odataClient()->from(AnalysisGroup::wsName())->get();
        $SamplingPoints = $this->odataClient()->from(SamplingPoint::wsName())->get();
        // dd($SamplingPoints);
        $action = 'create';

        return view('staff.samples.create')->with(compact(
            'LabClients',
            'AnalysisGroups',
            'SamplingPoints',
            'action'
        ));
    }
    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'client_no' => 'string|nullable',
                'client_name' => 'string|nullable',
                'sample_type' => 'required',
                'sample_code' => 'required',
                'myAction' => 'required',
                'sample_collection_no' => 'string|nullable'
            ]
        );

        // dd($request->all());
        // Add empty string if null


        try {
            $service = $this->MySoapClient(config('app.cuStaffPortal'));
            $params = new \stdClass();
            $params->samplerName = session('authUser')['employeeNo'];
            $params->samplingDate = now()->format('Y-m-d');
            $params->samplingTime = now()->format('H:i:s');
            $params->sampleCode = $request->sample_code;
            $params->sampleType = $request->sample_type;
            $params->clientNo = $request->client_no;
            $params->clientName = $request->client_name;
            $params->myAction = $request->myAction;
            $params->sampleCollectionNo = $request->sample_collection_no;
            $result = $service->FnSampleCollection($params);
            // dd($result);
            return redirect()->route('sampling.index')->with('success', 'Save Successful');
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
        }
    }
    public function show($id)
    {
        // dd($id);
        $LabClients = $this->odataClient()->from(LaboratoryClient::wsName())->get();
        $AnalysisGroups = $this->odataClient()->from(AnalysisGroup::wsName())->get();
        $AnalysisService = $this->odataClient()->from(AnalysisService::wsName())->get();
        $SamplingPoints = $this->odataClient()->from(SamplingPoint::wsName())
            ->where('Sample_Collection_No', $id)
            ->get();
        $SampleCollectionHeader = $this->odataClient()->from(SampleCollection::wsName())
            ->where('Sample_Collection_No', $id)
            ->first();
        $action = 'edit';
        // dd($SampleCollectionHeader);

        return view('staff.samples.update')->with(compact(
            'LabClients',
            'AnalysisGroups',
            'AnalysisService',
            'SamplingPoints',
            'action',
            'SampleCollectionHeader'
        ));
        ;
    }
    public function edit($id)
    {
        $LabClients = $this->odataClient()->from(LaboratoryClient::wsName())->get();
        $AnalysisGroups = $this->odataClient()->from(AnalysisGroup::wsName())->get();
        $SamplingPoints = $this->odataClient()->from(SamplingPoint::wsName())
            ->where('Sample_Collection_No', $id)
            ->get();
        // dd($SamplingPoints);
        $action = 'edit';

        return view('staff.samples.update')->with(compact(
            'LabClients',
            'AnalysisGroups',
            'SamplingPoints',
            'action'
        ));
    }
    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'clientNo' => 'required',
                'Client Name' => 'required',
                'samplingDate' => 'date|required',
                'samplingTime' => 'time|required',
                'sampleType' => 'required',
                'sampleCode' => 'required',
            ]
        );

        return view('staff.samples.index')->with('data', $request->all());
    }
    public function destroy($id)
    {
        return redirect()->back();
    }
    public function getAnalysisServicesByCode($AnalysisGroupCode)
    {
        // Implemetation of getting 
        $analysisServices = $this->odataClient()->from(AnalysisService::wsName())
            ->where('Analysis_Code', $AnalysisGroupCode)
            ->get();
        // dd($analysisServices);
        return response()->json($analysisServices);
    }
    public function UpdateParameterFindings(Request $request, $id)
    {
        // dd($request->all());

        $this->validate(
            $request,
            [
                'weather_conditions' => 'string',
                'temperature' => 'numeric',
                'sampling_area' => 'string',
                'activities' => 'string',
                'water_temperature' => 'numeric',
                'type_of_sample' => 'string',
                'sampling_method' => 'string',
                'equipment_used' => 'string',
                'sampling_depth' => 'string',
                'container_type' => 'string',
                'preservation_agent' => 'string',
            ]
        );


        try {
            $service = $this->MySoapClient(config('app.cuStaffPortal'));
            $params = new \stdClass();

            $params->collectionNo = $id;
            $params->weatherConditions = $request->weather_conditions;
            $params->temperature = $request->temperature;
            $params->samplingArea = $request->sampling_area;
            $params->activities = $request->activities;
            $params->waterTemperature = $request->water_temperature;
            $params->sampleType = $request->type_of_sample;
            $params->samplingMethod = $request->sampling_method;
            $params->equipmentUsed = $request->equipment_used;
            $params->samplingDepth = $request->sampling_depth;
            $params->containerType = $request->ContainerType;
            $params->preservationAgentUsed = $request->preservation_agent;

            // dd($params);
            $result = $service->FnSaveParameterFindings($params);
            return response()->json($result);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function SaveSamplingPoints(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'analysis_category' => 'string|required',
                'service_code' => 'string|required',
                'point_name' => 'string|required',
                'sample_collected' => 'string|required',
                'reason' => 'numeric',
            ]
        );
        // dd($request->all());

        try {
            $service = $this->MySoapClient(config('app.cuStaffPortal'));
            $params = new \stdClass();

            $params->collectionNo = $id;
            $params->analysisCategory = $request->analysis_category;
            $params->serviceCode = $request->service_code;
            $params->pointName = $request->point_name;
            $params->sampleCollected = $request->sample_collected;
            $params->reason = $request->Reason;
            $params->recordLineNo = 0;
            $params->myAction = 'create';

            // dd($params);
            $result = $service->FnSaveSamplingPoints($params);
            return response()->json($result);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function DeleteSamplingPoints(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'CollectionNo' => 'required',
            ]
        );

        try {
            $service = $this->MySoapClient(config('app.cuStaffPortal'));
            $params = new \stdClass();

            $params->collectionNo = $request->CollectionNo;
            $params->analysisCategory = '';
            $params->serviceCode = '';
            $params->pointName = '';
            $params->sampleCollected = '';
            $params->reason = '';
            $params->recordLineNo = $id;
            $params->myAction = 'delete';

            $result = $service->FnSaveSamplingPoints($params);
            return response()->json($result);

        } catch (\Throwable $th) {
            throw $th;
        }
    }

}
