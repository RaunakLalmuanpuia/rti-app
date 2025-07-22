<?php

namespace App\Http\Controllers;

use App\Models\Information;
use App\Repositories\InformationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CicController extends Controller
{
    //
    private $repository;
    public function __construct(InformationRepository $informationRepository)
    {
        $this->repository = $informationRepository;
    }
    public function index(){
        return Inertia::render('Backend/CIC/Index');
    }

    public function pendingJson(Request $request){

        $search = $request->input('filter');
        $perPage = $request->input('rowsPerPage', 15);

        $query = Information::with('department')
            ->whereNotNull('second_appeal_cic_in')
            ->whereNotNull('second_appeal_citizen_question')
            ->whereNull('second_appeal_cic_answer')
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

        $query = Information::with('department')
            ->whereNotNull('second_appeal_cic_in')
            ->whereNotNull('second_appeal_citizen_question')
            ->whereNotNull('second_appeal_cic_answer')
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
        return Inertia::render('Backend/CIC/Show',[
            'info' => $information
        ]);
    }

    public function store(Request $request, Information $information){

        $validated = $request->validate([
            'reply' => ['required', 'string', 'min:5', 'max:1000'],
            'attachment' => ['nullable','array'],
            'attachment.*' => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        $secondAppealReply= $this->repository->storeSecondAppealReply($information,$validated);

        abort_if(blank($secondAppealReply),500,'Something Went Wrong');

        // Notify applicant sms -application updated

        return response()->json([
            'status' => 'success',
            'message' => 'Answer saved successfully.'
        ]);
    }


    public function complainIndex(Request $request){

        return Inertia::render('Backend/CIC/Complain/Index');
    }

    public function complainJson(Request $request){
        $search = $request->input('filter');
        $perPage = $request->input('rowsPerPage', 15);

        $query = Information::with('department')
            ->whereNotNull('complain');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('citizen_name', 'like', "%{$search}%")
                    ->orWhere('citizen_question', 'like', "%{$search}%");
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

    public function complainShow(Information $information){
        $information->load(['department','local_council','paidAttachments']);
        return Inertia::render('Backend/CIC/Show',[
            'info' => $information
        ]);
    }

}
