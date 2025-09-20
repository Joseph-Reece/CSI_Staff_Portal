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
                'client_no' => 'required',
                'client_name' => 'required',
                'sample_type' => 'required',
                'sample_code' => 'required',
            ]
        );
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
            $params->myAction = 'create';
            $params->sampleCollectionNo = '';
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
            ->where('Sample_Collection_No',  $id)
            ->get();
        $SampleCollectionHeader = $this->odataClient()->from(SampleCollection::wsName())
            ->where('Sample_Collection_No',  $id)
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
            ->where('Sample_Collection_No',  $id)
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

}
