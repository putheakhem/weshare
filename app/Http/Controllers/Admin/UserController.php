<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 1)->get()->map(function ($user) {
            $user->created_at_formatted = Carbon::parse($user->created_at)->format('d-m-Y H:i:s');
            $user->updated_at_formatted = Carbon::parse($user->updated_at)->format('d-m-Y H:i:s');
            return $user;
        });

        return Inertia::render('Admin/ManageUsers/Manage_Users', [
            'users' => $users,
        ]);
    }
    public function edit(User $user)
    {
        return Inertia::render(
            'Admin/ManageUsers/Manage_Users',
            [
                'user' => $user
            ]
        );
    }
    public function update(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required'
        ]);
        $user->status = $request->status;
        $user->save();
        return redirect()->route('manage_users')->with('message', 'User Updated Successfully');
    }
}