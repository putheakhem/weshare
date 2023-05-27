<?php

namespace App\Http\Controllers\Files;

use App\Http\Controllers\Controller;
use App\Models\FileDetails;
use App\Models\Items;
use App\Models\Majors;
use App\Models\Types;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyDocumentController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $search = $request->input('search');
        $files = Items::join('users', 'items.user_id', '=', 'users.id')
            ->join('file_details', 'items.file_de_id', '=', 'file_details.id')
            ->join('majors', 'file_details.major_id', '=', 'majors.id')
            ->join('types', 'file_details.filetype_id', '=', 'types.id')
            ->leftjoin('favourite_items', function ($join) use ($userId) {
                $join->on('items.id', '=', 'favourite_items.item_id')
                    ->where('favourite_items.user_id', '=', $userId);
            })
            ->where('items.user_id', '=' , $userId) // Filter files by the authenticated user
            ->select('items.*', 'users.name as user_name', 'file_details.title as title', 'file_details.desc as description', 'file_details.major_id as major_id', 'file_details.filetype_id as type_id', 'majors.name as major_name', 'types.name as file_type')
            ->selectRaw('IF(favourite_items.user_id IS NOT NULL, true, false) as is_favorite')
            ->when($search, function ($query, $search) {
                return $query->where('file_details.title', 'LIKE', '%' . $search . '%')
                    ->orWhere('majors.name', 'LIKE', '%' . $search . '%')
                    ->orWhere('types.name', 'LIKE', '%' . $search . '%')
                    ->orWhere('items.filename', 'LIKE', '%' . $search . '%');
            })
            ->get();

            $majors = Majors::get();
            $types = Types::get();

        return Inertia::render('Files/MyDocuments', [
            'files' => $files,
            'majors' => $majors,
            'types' => $types
        ]);
    }

    public function update(Request $request, Items $item)
    {
        $request->validate([
            'filename' => 'nullable',
            'path' => 'nullable',
            'major_id' => 'required',
            'user_id' => 'required',
            'file_de_id' => 'required',
            'title' => 'required',
            'description' => 'nullable',
            'type_id' => 'required',
            'fileupload' => 'nullable|sometimes',
        ]);

        // Update the item details
        $item = Items::find($request->id);
        $item->filename = $request->filename;
        $item->path = $request->path;
        $item->user_id = $request->user_id;
        $item->file_de_id = $request->file_de_id;

        if ($request->hasFile('fileupload')) {

            $file = $request->file('fileupload');
            $newFilename = time() . '_' . $file->getClientOriginalName();
            $basename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $newPath = $file->storeAs('files', $newFilename, 'public');
    
            // Update the fileupload
            $item->filename = $basename;
            $item->path = $newPath;
        }
        $item->save();

        // Retrieve the existing file_details or create a new one if it doesn't exist
        $fileDetails = FileDetails::find($request->file_de_id);
        $fileDetails->major_id = $request->major_id;
        $fileDetails->filetype_id = $request->type_id;
        $fileDetails->title = $request->title;
        $fileDetails->desc = $request->description;
        $fileDetails->save();
        return redirect()->route('my_documents');
    }

    public function destroy(Request $request,Items $item)
    {
        
        $fileDetails = FileDetails::find($request->file_de_id);
        $fileDetails->delete();
        $item->delete();
        return redirect()->route('my_documents');
    }

}