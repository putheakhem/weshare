<?php

namespace App\Http\Controllers\UploadFile;

use App\Http\Controllers\Controller;
use App\Models\Majors;
use App\Models\Types;
use Inertia\Inertia;

class PostController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $majors = Majors::all();
        $filetype = Types::all();
        return Inertia::render('Files/UploadFile', [
           'majors' => $majors,
           'filetype' => $filetype
        ]);
    }
}
