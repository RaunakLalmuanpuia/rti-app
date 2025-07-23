<?php

namespace App\Http\Controllers;

use App\Models\Information;
use App\Repositories\InformationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SpioController extends Controller
{
    //
    private $repository;

    public function __construct(InformationRepository $informationRepository)
    {
        $this->repository = $informationRepository;
    }
    public function index(Request $request){

        return Inertia::render('Backend/SPIO/Index');
    }
    public function pendingJson(Request $request){

        $search = $request->input('filter');
        $perPage = $request->input('rowsPerPage', 15);
        $userDepartmentId =Auth::user()->department;

        $query = Information::with('department')
            ->where('citizen_question_department', $userDepartmentId)
            ->whereNull('spio_answer')
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
    public function answeredJson(Request $request){

        $search = $request->input('filter');
        $perPage = $request->input('rowsPerPage', 15);
        $userDepartmentId =Auth::user()->department;

        $query = Information::with('department')
            ->where('citizen_question_department', $userDepartmentId)
            ->whereNotNull('spio_answer')
            ->where('information_status', 1)
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
    public function computerGeneratedJson(Request $request){

        $search = $request->input('filter');
        $perPage = $request->input('rowsPerPage', 15);
        $userDepartmentId =Auth::user()->department;

        $query = Information::with('department')
            ->where('citizen_question_department', $userDepartmentId)
            ->where('information_status', 11)
            ->whereNotNull('spio_answer')
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
        $information->load(['department','local_council','paidAttachments']);
        return Inertia::render('Backend/SPIO/Show',[
            'info' => $information
        ]);
    }

    public function store(Request $request, Information $information){

        $validated=$request->validate([
            'answer' => ['required', 'string', 'min:3'],
            'attachment' => ['nullable', 'array', 'max:8'], // max 8 files
            'attachment.*' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'is_free' => ['required_with:attachment', 'boolean'],
            'attachment_price' => ['required_if:is_free,false', 'nullable', 'numeric', 'min:0'],
        ]);

        $answer=$this->repository->storeAnswer($information,$validated);

        abort_if(blank($answer),500,'Something Went Wrong');

        return response()->json([
            'status' => 'success',
            'message' => 'Answer saved successfully.'
        ]);
    }

    public function update(Request $request, Information $information){

    }
    public function transfer(Request $request, Information $information){
//        dd($request->all());
        $validated = $request->validate([
            'remark' => ['required', 'string', 'min:5', 'max:1000'],
            'department_id'   => ['required', 'exists:departments,id'],
        ]);

        $transfer = $this->repository->transferInformation($information, $validated);

        abort_if(blank($transfer),500,'Something Went Wrong');

        //NOTIFY THE USER - Application Transfered

        //NOTIFY THE DEPARTMENT

        return response()->json([
            'status' => 'success',
            'message' => 'Application transfered successfully.'
        ]);

    }


}
