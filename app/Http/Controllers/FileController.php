<?php

namespace App\Http\Controllers;

use App\Models\Items;

use Inertia\Inertia;

class FileController extends Controller
{
    public function index()
    {
        $files = Items::with('file_details')->get();
        return Inertia::render('Admin/ManageFiles/Manage_Files', [
           'files' => $files
        ]);
    }
}