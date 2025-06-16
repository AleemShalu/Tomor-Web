<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    public function upload(Request $request)
    {
        // Validate file size to demonstrate the error
        $request->validate([
            'file' => 'required|file|max:51200', // Max size in kilobytes (50MB)
        ]);

        // Handle the file upload
        $file = $request->file('file');
        $path = $file->store('uploads');

        return response()->json([
            'message' => 'File uploaded successfully',
            'path' => $path,
        ]);
    }
}
