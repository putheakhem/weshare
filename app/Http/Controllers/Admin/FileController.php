<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FileDetails;
use App\Models\Items;
use App\Models\Majors;
use App\Models\Types;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class FileController extends Controller
{
    public function index()
    {
        $files = Items::join('users', 'items.user_id', '=', 'users.id')
            ->join('file_details', 'items.file_de_id', '=', 'file_details.id')
            ->join('majors', 'file_details.major_id', '=', 'majors.id')
            ->join('types', 'file_details.filetype_id', '=', 'types.id')
            ->select('items.*', 'users.name as user_name', 'file_details.title as title', 'file_details.desc as description', 'file_details.major_id as major_id', 'file_details.filetype_id as type_id', 'majors.name as major_name', 'types.name as file_type')
            ->get()
            ->map(function ($file) {
                $file->created_at_formatted = Carbon::parse($file->created_at)->format('d-m-Y H:i:s');
                $file->updated_at_formatted = Carbon::parse($file->updated_at)->format('d-m-Y H:i:s');
                return $file;
            });

        $majors = Majors::get();
        $types = Types::get();

        return Inertia::render('Admin/ManageFiles/Manage_Files', [
            'files' => $files,
            'majors' => $majors,
            'types' => $types
        ]);
    }
    public function edit(Items $file)
    {
        return Inertia::render(
            'Admin/ManageFiles/Manage_Files',
            [
                'file' => $file
            ]
        );
    }
    public function upload(Request $request)
    {
        $request->validate([
            'fileupload' => 'required|mimes:jpg,jpeg,png,csv,txt,xlx,xls,pdf|max:1000000',
            'major_id' => 'required',
            'title' => 'required',
            'description' => 'nullable',
            'type_id' => 'required'
        ]);

        $user_id = Auth::id();
        $major_id = $request->input('major_id');
        $type_id = $request->input('type_id');
        $title = $request->input('title');
        $description = $request->input('description');

        if ($request->file()) {
            $file = $request->file('fileupload');
            $filename = time() . '_' . $file->getClientOriginalName();
            $basename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $path = $file->storeAs('files', $filename, 'public');

            // Save file details
            $fileDetails = new FileDetails();
            $fileDetails->major_id = $major_id;
            $fileDetails->filetype_id = $type_id;
            $fileDetails->title = $title;
            $fileDetails->desc = $description;
            $fileDetails->save();

            // Get the file_de_id from the saved fileDetails record
            $file_de_id = $fileDetails->id;
            $fileUpload = new Items();
            $fileUpload->user_id = $user_id;
            $fileUpload->file_de_id = $file_de_id;
            $fileUpload->filename = $basename;
            $fileUpload->path = $path;
            $fileUpload->save();
            return redirect()->route('manage_files');
        }
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
        return redirect()->route('manage_files');
    }
    public function destroy(Request $request,Items $item)
    {
        $item = Items::find($request->id);
        $item->delete();
        $fileDetails = FileDetails::find($request->file_de_id);
        $fileDetails->delete();
        return redirect()->route('manage_files');
    }
}