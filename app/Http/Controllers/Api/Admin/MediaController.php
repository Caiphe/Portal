<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function upload(Request $request)
    {
        $path = $request->file('upload')->store('editor', 'public');

        return response()->json([
            'success' => true,
            'url' => '/storage/' . $path
        ], 201);
    }
}
