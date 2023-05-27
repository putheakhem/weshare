<?php

namespace App\Http\Controllers;

use App\Models\Items;
use App\Models\Majors;
use App\Models\Types;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request->input('query');
        $data = Items::where('filename', 'like', "%{$query}%")
        ->orWhereHas('file_details.majors', function ($innerQuery) use ($query) {
            $innerQuery->where('name', 'like', "%{$query}%");
        })->get();    

        return Inertia::render('Search/searchresult', [
            'data' => $data,
            'query' => $query
        ]);
    }
    public function departmentfilter($id)
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
            ->when($id, function ($query, $id) {
                return $query->where('majors.id', '=', $id);
            })
            ->get();
        foreach ($files as $file) {
            Log::debug('is_favorite', ['is_favorite' => $file->is_favorite]);
        }
        $types = Types::all();
     
        return Inertia::render('Search/SearchDepartment', [
            'files' => $files,
            'types' => $types,
            'search' => $id
        ]);
    }
  
}
