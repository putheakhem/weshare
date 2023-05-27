<?php

namespace App\Http\Controllers\UploadFile;

use App\Http\Controllers\Controller;
use App\Models\TemporaryFile;
use Illuminate\Support\Facades\Storage;

class DeleteTemporaryFileController extends Controller
{
    public function __invoke()
    {
        $temporaryfile = TemporaryFile::where('folder', request()->getContent())->first();
        if ($temporaryfile) {
            Storage::deleteDirectory('files/tmp/' . $temporaryfile->folder);
            $temporaryfile->delete();
        }

        return response()->noContent();
    }
}
