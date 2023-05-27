<?php

namespace App\Http\Controllers\UploadFile;

use App\Http\Controllers\Controller;
use App\Models\TemporaryFile;
use Illuminate\Http\Request;

class UploadFileController extends Controller
{
    public function __invoke(Request $request)
    {
        
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $folder = uniqid('file-', true);
            $file->storeAs('files/' . $folder, $fileName,'public');

            TemporaryFile::create([
                'folder' => $folder,
                'file' => $fileName
            ]);

            return $folder;
        }
        return '';
    }
}
