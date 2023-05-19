<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\Items;
use App\Models\Majors;
use App\Models\Types;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

use function PHPSTORM_META\type;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $majors = Majors::all();
        $filetype = Types::all();
        return Inertia::render('Dashboard', [
            'majors' => $majors,
            'filetype' => $filetype
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LoginRequest $request)
    {
        Validator::make($request->all(), [
            'major_id' => 'required',
            'filetype_id' => 'required',
            'filename' => 'required'
        ])->validate();

        $item = Items::create($request->only(['major_id', 'filetype_id', 'filename']));

        $this->processFile($request, $item);

        return redirect()->back()
            ->with('message', 'File Upload');
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @param  \App\Models\Items $item
     * @return \Illuminate\Http\Response
     */
    public function update(LoginRequest $request, Items $item)
    {
        Validator::make($request->all(), [
            'major_id' => 'required',
            'filetype_id' => 'required',
            'filename' => 'required'
        ])->validate();

        $item->update($request->only(['major_id', 'filetype_id', 'filename']));

        $this->processFile($request, $item);

        return redirect()->back()
            ->with('message', 'File updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Items  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Items $item)
    {
        $item->delete();
        return redirect()->back()
            ->with('message', 'File deleted');
    }

    public function upload(\Illuminate\Http\Request $request)
    {
        if ($request->hasFile('fileFilepond')) {
            return $request->file('fileFilepond')->store('Files', 'public');
        }
        return '';
    }

    public function uploadRevert(Request $request)
    {
        if ($file = $request->get('file')) {
            $path = storage_path('app/public/' . $file);
            if (file_exists($path)) {
                unlink($path);
            }
        }
        return '';
    }

    protected function processFile(Request $request, Items $item = null)
    {

        $files = $request->get('file') ? explode('|', $request->get('file')) : [];

        foreach ($files as $file) {
            if (!$item->hasImage($file)) {
                $path = storage_path('app/public/Files/' . $file);
                if (file_exists($path)) {
                    copy($path, public_path($file));
                    unlink($path);
                }
            }
        }

        foreach ($item->findMissingImages($files) as $fi) {
            if (file_exists(public_path($fi))) {
                unlink(public_path($fi));
            }
        }

        $item->update([
            'file' => $request->get('file')
        ]);
    }
}
