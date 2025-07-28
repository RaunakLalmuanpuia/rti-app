<?php

namespace App\Http\Controllers;

use App\Models\Information;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    //
    public function index(Request $request)
    {

        $user = $request->user();

        //Citizen
        if ($user->role === 0) {
            return to_route('dashboard.citizen');
        }

        if ($user->role === 10) {
            return to_route('dashboard.admin');
        }

        if ($user->role === 5) {
            if($user->status === 'Accept'){
                return to_route('dashboard.official');
            }
            else{
                return Inertia::render('Frontend/Auth/Register/Pending');
            }
        }

        return inertia('Frontend/Auth/Register/Pending');
    }

    public function citizen(Request $request)
    {

        $user = Auth::user();

        $search = $request->input('search');
        $perPage = $request->input('perPage', 10);

        $query = Information::with('department','local_council')
            ->where('user_id', $user->id)
            ->whereNull('complain')
            ->whereNull('transfer');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('citizen_name', 'like', "%$search%")
                    ->orWhere('citizen_question', 'like', "%$search%")
                    ->orWhereHas('department', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%$search%");
                    });
            });
        }

        $information = $query->orderBy('updated_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return Inertia::render('Backend/Dashboard/Citizen', [
            'information' => $information,
            'search' => $search,
            'perPage' => $perPage,
        ]);

    }

    public function admin()
    {
        return inertia('Backend/Dashboard/Admin');
    }


    public function official()
    {

        return inertia('Backend/Dashboard/Official',[

        ]);

    }


}
