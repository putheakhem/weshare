<?php

namespace App\Http\Controllers\UploadFile;

use App\Http\Controllers\Controller;
use App\Models\FileDetails;
use App\Models\Items;
use App\Models\TemporaryFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class StorePostController extends Controller
{
    public function __invoke(Request $request)
    {
        
        $validator = Validator::make($request->all(),(
            [
                'major_id' => 'required',
                'filetype_id' => 'required',
                'title' => 'required',
                'desc' => 'required'
            ]
        ));
       
        $temporaryFiles = TemporaryFile::all();

        if ($validator->fails()) {
            foreach ($temporaryFiles as $temporaryFile) {
                Storage::deleteDirectory('files/tmp/' . $temporaryFile->folder);
                $temporaryFile->delete();
            }
            return to_route('my_documents')->withErrors($validator)->withInput();
        }
        $data = $validator->validated();
        $file_detail = FileDetails::create(
            [
                'major_id' => $data['major_id']['id'],
                'filetype_id' => $data['filetype_id']['id'],
                'title' => $data['title'],
                'desc' => $data['desc'],
            ]
        );
        $user_id = Auth::user()->id;

        foreach ($temporaryFiles as $temporaryFile) {
            Storage::copy('files/tmp/' . $temporaryFile->folder . '/' . $temporaryFile->file, 'files/' . $temporaryFile->folder . '/' . $temporaryFile->file);
            
                Items::create([
                    'user_id' => $user_id,
                    'file_de_id' => $file_detail->id,
                    'filename' => $temporaryFile->file,
                    'path' => 'files/'.$temporaryFile->folder . '/' . $temporaryFile->file
                ]);
         
            Storage::deleteDirectory('files/tmp/' . $temporaryFile->folder);
            $temporaryFile->delete();
        }
        return redirect()->route('my_documents');
    }
}
