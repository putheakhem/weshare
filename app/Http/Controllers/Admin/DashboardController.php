<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedbacks;
use App\Models\Items;
use App\Models\Majors;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $userCount = User::where('role', 1)->count();
        $fileCount = Items::count();

        $majorCounts = Majors::leftJoin('file_details', 'majors.id', '=', 'file_details.major_id')
        ->leftJoin('items', 'file_details.id', '=', 'items.file_de_id')
        ->select('majors.name', DB::raw('COUNT(items.id) as total_items'))
        ->groupBy('majors.name')
        ->get();

        $feedbacks = Feedbacks::join('users', 'feedbacks.user_id', '=', 'users.id')
            ->select('feedbacks.*', 'users.name as user_name', 'users.email as user_email')
            ->get()->map(function ($feedback) {
                $feedback->created_at_formatted = Carbon::parse($feedback->created_at)->format('d-m-Y H:i:s');
                return $feedback;
            });

        $feedbackCount = Feedbacks::count();

        return Inertia::render('Admin/Dashboard', [
            'userCount' => $userCount,
            'fileCount' => $fileCount,
            'feedbacks' => $feedbacks,
            'feedbackCount' => $feedbackCount,
            'majorCounts' => $majorCounts
        ]);
    }

    public function edit(Feedbacks $feedback)
    {
        return Inertia::render(
            'Admin/Dashboard',
            [
                'feedback' => $feedback
            ]
        );
    }

    public function update(Request $request, Feedbacks $feedback)
    {
        $request->validate([
            'status' => 'required'
        ]);
        $feedback->status = $request->status;
        $feedback->save();
        return redirect()->route('dashboard');
    }

    public function destroy(Feedbacks $feedback)
    {
        $feedback->delete();
        return redirect()->route('dashboard');
    }
}