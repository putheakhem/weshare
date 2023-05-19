<?php

namespace App\Http\Controllers;

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
       
        $temporaryImages = TemporaryFile::all();
        if ($validator->fails()) {
            foreach ($temporaryImages as $temporaryImage) {
                Storage::deleteDirectory('images/tmp/' . $temporaryImage->folder);
                $temporaryImage->delete();
            }
            return to_route('weshare')->withErrors($validator)->withInput();
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

        foreach ($temporaryImages as $temporaryImage) {
            Storage::copy('images/tmp/' . $temporaryImage->folder . '/' . $temporaryImage->file, 'images/' . $temporaryImage->folder . '/' . $temporaryImage->file);
            
                Items::create([
                    'user_id' => $user_id,
                    'file_de_id' => $file_detail->id,
                    'filename' => $temporaryImage->file,
                    'path' => $temporaryImage->folder . '/' . $temporaryImage->file
                ]);
         
            Storage::deleteDirectory('images/tmp/' . $temporaryImage->folder);
            $temporaryImage->delete();
        }


        return redirect()->route('weshare');
    }
}
