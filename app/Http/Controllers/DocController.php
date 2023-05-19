<?php

namespace App\Http\Controllers;

use App\Models\Items;

use Inertia\Inertia;

class DocController extends Controller
{
    public function __invoke()
    {
        $data = Items::with('file_details')->get();
        return Inertia::render('File/showfile', [
           'data' => $data
        ]);
    }
    public function showfile()
    {
     
        return Inertia::render('File/FileDetail');
    }
}
