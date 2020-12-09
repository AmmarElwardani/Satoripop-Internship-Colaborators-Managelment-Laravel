<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Department;
use App\Company;

class DepartmentCompanyController extends Controller
{
    public function getDepartments() {
        return response()->json(
            Department::all(),
            200
        );
    }
    public function getCompanies() {
        return response()->json(
            Company::all(),
            200
        );
    }
}
