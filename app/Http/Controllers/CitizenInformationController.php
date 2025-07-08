<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\District;
use App\Models\LocalCouncil;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CitizenInformationController extends Controller
{
    public function create(){

        return Inertia::render('Backend/Citizen/Information/Create');
    }

    public function searchDepartment(Request $request)
    {
        $search = $request->input('search', '');

        $departments = Department::select('id', 'name')
            ->where('name', 'LIKE', "%{$search}%")
            ->whereNotIn('name', [
                'AMC(LC)', 'LMC(LC)', 'Local Administration Department (Secretariat)'
            ])
            ->whereHas('users', function ($query) {
                $query->where('bio', 'spio')->where('status', 'Accept');
            })
            ->limit(20)
            ->get();

        return response()->json($departments);
    }

    public function getLocalCouncil(Request $request){


        $councils = LocalCouncil::where('district', $request->district)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($councils);

    }

    public function store(Request $request){
        dd($request->all());


    }


}
