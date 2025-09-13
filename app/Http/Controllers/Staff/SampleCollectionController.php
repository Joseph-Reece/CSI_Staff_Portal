<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\SampleCollection;
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
        
        $SamplesCollected = $this->odataClient()->from(SampleCollection::wsName())->get();
        // $data = SampleCollection::all();
        $data = ["SampleCollections" => $SamplesCollected];
        // dd("Samples", $data);
        
        return view('staff.samples.index')->with($data);
    }
    public function create()
    {
        return view('staff.samples.create');
    }
    public function store(Request $request)
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
    public function show($id)
    {
        return view('staff.samples.create');
    }
    public function edit($id)
    {
        return view('staff.samples.edit')->with('data', $id);
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

}
