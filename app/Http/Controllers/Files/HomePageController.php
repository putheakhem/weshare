<?php

namespace App\Http\Controllers\Files;

use App\Http\Controllers\Controller;
use App\Models\Items;
use App\Models\Majors;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HomePageController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $files = Items::join('users', 'items.user_id', '=', 'users.id')
            ->join('file_details', 'items.file_de_id', '=', 'file_details.id')
            ->join('majors', 'file_details.major_id', '=', 'majors.id')
            ->join('types', 'file_details.filetype_id', '=', 'types.id')
            ->leftJoin('favourite_items', function ($join) use ($userId) {
                $join->on('items.id', '=', 'favourite_items.item_id')
                    ->where('favourite_items.user_id', '=', $userId);
            })
            ->select('items.*', 'users.name as user_name', 'file_details.title as title', 'file_details.desc as description', 'file_details.major_id as major_id', 'file_details.filetype_id as type_id', 'majors.name as major_name', 'types.name as file_type')
            ->selectRaw('IF(favourite_items.user_id IS NOT NULL, true, false) as is_favorite')
            ->get();
        //select major
        $majors = Majors::all();
        return Inertia::render('Home', [
            'files' => $files,
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'majors' => $majors,
        ]);
    }
    public function files(Request $request)
    {
        $userId = Auth::id();
        $search = $request->input('search');
        $files = Items::join('users', 'items.user_id', '=', 'users.id')
            ->join('file_details', 'items.file_de_id', '=', 'file_details.id')
            ->join('majors', 'file_details.major_id', '=', 'majors.id')
            ->join('types', 'file_details.filetype_id', '=', 'types.id')
            ->leftJoin('favourite_items', function ($join) use ($userId) {
                $join->on('items.id', '=', 'favourite_items.item_id')
                    ->where('favourite_items.user_id', '=', $userId);
            })
            ->select('items.*', 'users.name as user_name', 'file_details.title as title', 'file_details.desc as description', 'file_details.major_id as major_id', 'file_details.filetype_id as type_id', 'majors.name as major_name', 'types.name as file_type')
            ->selectRaw('IF(favourite_items.user_id IS NOT NULL, true, false) as is_favorite')
            ->when($search, function ($query, $search) {
                return $query->where('file_details.title', 'LIKE', '%' . $search . '%')
                    ->orWhere('majors.name', 'LIKE', '%' . $search . '%')
                    ->orWhere('types.name', 'LIKE', '%' . $search . '%')
                ;
            })
            ->get();
        foreach ($files as $file) {
            Log::debug('is_favorite', ['is_favorite' => $file->is_favorite]);
        }
        return Inertia::render('Files/Display_All_Files', [
            'files' => $files,
            'search' => $search
        ]);
    }

    public function file_detail($id)
    {
        $userId = Auth::id();
        $file = Items::join('users', 'items.user_id', '=', 'users.id')
            ->join('file_details', 'items.file_de_id', '=', 'file_details.id')
            ->join('majors', 'file_details.major_id', '=', 'majors.id')
            ->join('types', 'file_details.filetype_id', '=', 'types.id')
            ->leftJoin('favourite_items', function ($join) use ($userId) {
                $join->on('items.id', '=', 'favourite_items.item_id')
                    ->where('favourite_items.user_id', '=', $userId);
            })
            ->select('items.*', 'users.name as user_name', 'file_details.title as title', 'file_details.desc as description', 'file_details.major_id as major_id', 'file_details.filetype_id as type_id', 'majors.name as major_name', 'types.name as file_type')
            ->selectRaw('IF(favourite_items.user_id IS NOT NULL, true, false) as is_favorite')
            ->where('items.id', $id)
            ->first();

        $files = Items::join('users', 'items.user_id', '=', 'users.id')
            ->join('file_details', 'items.file_de_id', '=', 'file_details.id')
            ->join('majors', 'file_details.major_id', '=', 'majors.id')
            ->join('types', 'file_details.filetype_id', '=', 'types.id')
            ->leftJoin('favourite_items', function ($join) use ($userId) {
                $join->on('items.id', '=', 'favourite_items.item_id')
                    ->where('favourite_items.user_id', '=', $userId);
            })
            ->select('items.*', 'users.name as user_name', 'file_details.title as title', 'file_details.desc as description', 'file_details.major_id as major_id', 'file_details.filetype_id as type_id', 'majors.name as major_name', 'types.name as file_type')
            ->selectRaw('IF(favourite_items.user_id IS NOT NULL, true, false) as is_favorite')
            ->get();

        if (!$file) {
            // Handle the case where the file with the given ID is not found
            abort(404);
        }
        
        return Inertia::render('Files/FileDetail', [
           
            'file' => $file,
            'files' => $files
        ]);
    }
}