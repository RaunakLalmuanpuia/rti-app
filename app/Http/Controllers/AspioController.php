<?php

namespace App\Http\Controllers;



use App\Models\Information;
use App\Models\User;
use App\Repositories\InformationRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;


class AspioController extends Controller
{
    //
    private $repository;

    public function __construct(InformationRepository $informationRepository)
    {
        $this->repository = $informationRepository;
    }
    public function index(Request $request){

        return Inertia::render('Backend/SAPIO/Index');

    }

    public function pendingJson(Request $request){

        $search = $request->input('filter');
        $perPage = $request->input('rowsPerPage', 15);
        $userDepartmentId =Auth::user()->department;

        $query = Information::with('department')
            ->where('citizen_question_department', $userDepartmentId)
            ->whereNull('aspio_answer')
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
    public function commentedJson(Request $request){

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
        $information->load(['department','local_council','paidAttachments']);
        return Inertia::render('Backend/SAPIO/Show',[
            'info' => $information
        ]);
    }
    public function store(Request $request, Information $information){
        $validated = $request->validate([
            'comment' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        $mySpioList = User::where('department',$information->citizen_question_department)->where('bio','spio')->where('status','Accept')->get();

        abort_if(blank($mySpioList),500,'No SPIO Registered');

        $comment=$this->repository->storeComment($validated['comment'],$information);

        abort_if(blank($comment),500,'Something Went Wrong');

        // Notify SPIO Application received

        return response()->json([
            'status' => 'success',
            'message' => 'Comment saved successfully.'
        ]);

    }
}
