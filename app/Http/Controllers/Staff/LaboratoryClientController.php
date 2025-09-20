<?php

namespace App\Http\Controllers\Staff;

use App\Traits\WebServicesTrait;
use Illuminate\Http\Request;

class LaboratoryClientController extends Controller
{
    //
    use WebServicesTrait;
    public function __construct()
    {
        $this->middleware('isAuth');
        $this->middleware('staff');
    }
    public function index()
    {
        return view('staff');
    }
}
