<?php

namespace App\Http\Controllers;



use App\Models\Information;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;


class AspioController extends Controller
{
    //
    public function index(Request $request){

        return Inertia::render('Backend/SAPIO/Index');

    }

    public function pending(Request $request){

        $search = $request->input('filter');
        $perPage = $request->input('rowsPerPage', 15);
        $userDepartmentId =Auth::user()->department;

        $query = Information::with('department')
            ->where('citizen_question_department', $userDepartmentId)
            ->whereNull('complain')
            ->whereNull('transfer');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('citizen_name', 'like', "%{$search}%")
                    ->orWhere('citizen_question', 'like', "%{$search}%")
                    ->orWhereHas('department', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $list = $query
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage)
            ->appends($request->all());

        return response()->json([
            'list' => $list
        ]);

    }
    public function commented(Request $request){

        $search = $request->input('filter');
        $perPage = $request->input('rowsPerPage', 15);
        $userDepartmentId =Auth::user()->department;

        $query = Information::with('department')
            ->where('citizen_question_department', $userDepartmentId)
            ->whereNotNull('aspio_answer')
            ->whereNull('complain')
            ->whereNull('transfer');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('citizen_name', 'like', "%{$search}%")
                    ->orWhere('citizen_question', 'like', "%{$search}%")
                    ->orWhereHas('department', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $list = $query
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage)
            ->appends($request->all());

        return response()->json([
            'list' => $list
        ]);
    }

    public function show(Information $information){
//        dd($information);
        $information->load(['department','local_council','paidAttachments']);

        return Inertia::render('Backend/SAPIO/Show',[
            'info' => $information
        ]);
    }
    public function store(Request $request, Information $information){

    }
}
