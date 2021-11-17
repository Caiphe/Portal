<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function upload(Request $request)
    {
        $filename = date('Y/m/') . uniqid() . '.' . $request->file('upload')->extension();
        $path = $request->file('upload')->storeAs('editor', $filename, 'public');

        return response()->json([
            'success' => true,
            'url' => '/storage/' . $path
        ], 201);
    }
}
