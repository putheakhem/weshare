<?php

namespace App\Http\Controllers;

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
        return Inertia::render('Upload', [
           'majors' => $majors,
           'filetype' => $filetype
        ]);
    }
}
