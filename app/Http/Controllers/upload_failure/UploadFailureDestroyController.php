<?php

namespace App\Http\Controllers\upload_failure;

use App\Http\Controllers\Controller;
use App\UploadFailure;

class UploadFailureDestroyController extends Controller
{
    public function destroy($id)
    {
        $upload_failures = UploadFailure::findOrFail($id);
        $upload_failures->delete();

        return redirect()->route('upload_failure.index')->with('success', 'File berhasil dihapus!');
    }
}