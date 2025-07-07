<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class CitizenInformationController extends Controller
{

    public function create(){
        return Inertia::render('Backend/Citizen/Information/Create');
    }
}
