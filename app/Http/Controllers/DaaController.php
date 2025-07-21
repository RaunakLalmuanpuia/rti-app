<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Information;
use App\Repositories\InformationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DaaController extends Controller
{
    private $repository;
    public function __construct(InformationRepository $informationRepository)
    {
        $this->repository = $informationRepository;
    }
    public function index(){

        return Inertia::render('Backend/DAA/Index');
    }

    public function pendingJson(Request $request)
    {
        $search = $request->input('filter');
        $perPage = $request->input('rowsPerPage', 15);
        $user = Auth::user();

        // Convert comma-separated departments into array of ints
        $deptIds = collect(explode(',', $user->sex))
            ->map(fn($id) => (int) trim($id))
            ->filter()
            ->unique()
            ->values();

        $userDepartment = (int) $user->department;

        // Build combined query
        $query = Information::with('department')
            ->whereNotNull('first_appeal_citizen_question')
            ->whereNull('first_appeal_daa_answer')
            ->where('information_status', '!=', 22)
            ->whereNull('complain')
            ->whereNull('transfer')
            ->where(function ($q) use ($userDepartment, $deptIds) {
                $q->where('citizen_question_department', $userDepartment);

                if ($deptIds->isNotEmpty()) {
                    $q->orWhereIn('citizen_question_department', $deptIds);
                }
            });

        // Apply search if present
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('citizen_name', 'like', "%{$search}%")
                    ->orWhere('citizen_question', 'like', "%{$search}%")
                    ->orWhereHas('department', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $list = $query->orderBy('updated_at', 'desc')
            ->paginate($perPage)
            ->appends($request->all());

        return response()->json([
            'list' => $list
        ]);
    }

    public function answeredJson(Request $request){

        $search = $request->input('filter');
        $perPage = $request->input('rowsPerPage', 15);

        $user = Auth::user();

        // Convert comma-separated departments into array of ints
        $deptIds = collect(explode(',', $user->sex))
            ->map(fn($id) => (int) trim($id))
            ->filter()
            ->unique()
            ->values();

        $userDepartment = (int) $user->department;

        // Build combined query
        $query = Information::with('department')
            ->whereNotNull('first_appeal_daa_answer')
            ->where('information_status', '!=', 22)
            ->whereNull('complain')
            ->whereNull('transfer')
            ->where(function ($q) use ($userDepartment, $deptIds) {
                $q->where('citizen_question_department', $userDepartment);

                if ($deptIds->isNotEmpty()) {
                    $q->orWhereIn('citizen_question_department', $deptIds);
                }
            });


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

        $user = Auth::user();

        // Convert comma-separated departments into array of ints
        $deptIds = collect(explode(',', $user->sex))
            ->map(fn($id) => (int) trim($id))
            ->filter()
            ->unique()
            ->values();

        $userDepartment = (int) $user->department;

        // Build combined query
        $query = Information::with('department')
            ->whereNotNull('first_appeal_daa_answer')
            ->where('information_status',  22)
            ->whereNull('complain')
            ->whereNull('transfer')
            ->where(function ($q) use ($userDepartment, $deptIds) {
                $q->where('citizen_question_department', $userDepartment);

                if ($deptIds->isNotEmpty()) {
                    $q->orWhereIn('citizen_question_department', $deptIds);
                }
            });


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



    public function allApplicationJson(Request $request){

        $search = $request->input('filter');
        $perPage = $request->input('rowsPerPage', 15);

        $user = Auth::user();

        // Convert comma-separated departments into array of ints
        $deptIds = collect(explode(',', $user->sex))
            ->map(fn($id) => (int) trim($id))
            ->filter()
            ->unique()
            ->values();

        $userDepartment = (int) $user->department;

        // Build combined query
        $query = Information::with('department')
            ->whereNull('complain')
            ->whereNull('transfer')
            ->where(function ($q) use ($userDepartment, $deptIds) {
                $q->where('citizen_question_department', $userDepartment);

                if ($deptIds->isNotEmpty()) {
                    $q->orWhereIn('citizen_question_department', $deptIds);
                }
            });


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
        return Inertia::render('Backend/DAA/Show',[
            'info' => $information
        ]);
    }

    public function store(Request $request, Information $information){
//        dd($request->all());
        $validated = $request->validate([
            'reply' => ['required', 'string', 'min:5', 'max:1000'],
            'attachment' => ['nullable','array'],
            'attachment.*' => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        $firstAppealReply= $this->repository->storeFirstAppealReply($information,$validated);

        abort_if(blank($firstAppealReply),500,'Something Went Wrong');

        // Notify applicant sms -application updated

        return response()->json([
            'status' => 'success',
            'message' => 'Answer saved successfully.'
        ]);
    }

    public function update(Request $request, Information $information){

    }
}
