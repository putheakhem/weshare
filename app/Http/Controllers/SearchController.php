<?php

namespace App\Http\Controllers;

use App\Models\Items;
use App\Models\Majors;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request->input('query');

        // Perform the search query
        // $data = Items::where('filename', 'like', "%{$query}%")
        //     ->orWhere('file_de_id', 'like', "%{$query}%")
        //     ->get();
        
        // Perform the search query
        $data = Items::where('filename', 'like', "%{$query}%")
        ->orWhereHas('file_details.majors', function ($innerQuery) use ($query) {
            $innerQuery->where('name', 'like', "%{$query}%");
        })->get();    

        return Inertia::render('search/searchresult', [
            'data' => $data,
            'query' => $query
        ]);
    }
    public function departmentfilter($name)
    {
        $computerScienceItems = Items::whereHas('file_details', function ($query) use ($name) {
            $query->whereHas('majors', function ($query) use ($name) {
                $query->where('id', $name);
            });
        })->get();
        return Inertia::render('search/departmentfilter', [
            'data' =>  $computerScienceItems,
           
        ]);
    }
}
