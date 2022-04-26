<?php

namespace App\Http\Controllers\Companies;

use App\Models\Company;
use App\Models\Record;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class CompanyController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /** Function to get companies (NOT USED) */
    public function index(){

        return $companies = Company::get();
    }

}
