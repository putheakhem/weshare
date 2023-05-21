<?php

namespace App\Http\Controllers;

use App\Models\Types;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TypeController extends Controller
{
    public function index()
    {
        $types = Types::get()->map(function ($type) {
            $type->created_at_formatted = Carbon::parse($type->created_at)->format('d-m-Y H:i:s');
            $type->updated_at_formatted = Carbon::parse($type->updated_at)->format('d-m-Y H:i:s');
            return $type;
        });
        return Inertia::render('Admin/ManageTypes/Manage_Types', [
            'types' => $types,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        Types::create([
            'name' => $request->name,
        ]);
        return redirect()->route('manage_types')->with('message', 'Major Created Successfully');
    }
    public function edit(Types $type)
    {
        return Inertia::render(
            'Admin/ManageMajors/Manage_Majors',
            [
                'type' => $type
            ]
        );
    }
    public function update(Request $request, Types $type)
    {
        $request->validate([
            'name' => 'required'
        ]);
        $type->name = $request->name;
        $type->save();
        return redirect()->route('manage_types');
    }
    public function destroy(Types $type)
    {
        $type->delete();
        return redirect()->route('manage_types');
    }
}