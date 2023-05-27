<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Majors;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MajorController extends Controller
{
    public function index()
    {
        $majors = Majors::get()->map(function ($major) {
            $major->created_at_formatted = Carbon::parse($major->created_at)->format('d-m-Y H:i:s');
            $major->updated_at_formatted = Carbon::parse($major->updated_at)->format('d-m-Y H:i:s');
            return $major;
        });
        return Inertia::render('Admin/ManageMajors/Manage_Majors', [
            'majors' => $majors,
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        Majors::create([
            'name' => $request->name,
        ]);
        return redirect()->route('manage_majors');
    }
    public function edit(Majors $major)
    {
        return Inertia::render(
            'Admin/ManageMajors/Manage_Majors',
            [
                'major' => $major
            ]
        );
    }
    public function update(Request $request, Majors $major)
    {
        $request->validate([
            'name' => 'required'
        ]);
        $major->name = $request->name;
        $major->save();
        return redirect()->route('manage_majors');
    }
    public function destroy(Majors $major)
    {
        $major->delete();
        return redirect()->route('manage_majors');
    }
}