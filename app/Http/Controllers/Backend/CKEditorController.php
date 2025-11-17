<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use File;
class CKEditorController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->storeAs('uploads', $filename, 'public');

            $url = asset('storage/uploads/'.$filename);
            return response()->json([
                'uploaded' => true,
                'url' => $url
            ]);
        }
    }
   public function browse()
{
    $path = public_path('uploads/ckeditor'); // ✅ correct path, not URL

    // Create the folder if it doesn’t exist
    if (!\File::exists($path)) {
        \File::makeDirectory($path, 0755, true);
    }

    $files = \File::files($path);

    echo '<html><head><title>Image Browser</title></head><body>';
    echo '<h3>Select Image</h3>';
    echo '<style>
            img { max-width:150px; margin:10px; border:1px solid #ccc; cursor:pointer; }
            body { font-family: Arial; padding: 10px; }
          </style>';

    foreach ($files as $file) {
        $url = asset('uploads/ckeditor/' . basename($file));
        echo "<img src='{$url}' onclick='selectImage(\"{$url}\")'>";
    }

    echo '<script>
        function selectImage(url) {
            window.opener.CKEDITOR.tools.callFunction(1, url);
            window.close();
        }
    </script>';
    echo '</body></html>';
}

   



}


