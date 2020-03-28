<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        if (!\Auth::check()) {
            return redirect()->home();
        }

        return view('templates.user.show', [
            'user'=>\Auth::user()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validateOn = [
            'first_name' => 'required',
            'second_name' => 'required',
        ];

        if($request->email !== $user->email){
            $validateOn = array_merge($validateOn, ['email' => 'email|required|unique:users,email']);
        }

        if ($request->password !== null) {
            $validateOn = array_merge($validateOn, ['password' => 'confirmed']);
        }

        $user->update($request->validate($validateOn));

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function updateProfilePicture(Request $request)
    {

        // $image = $request->file('profile');
        // $imageName = base64_encode('jsklaf88sfjdsfjl' . $request->user()->id) . '.png';
        // $path = \Storage::putFileAs('profile', $request->file('profile'), $imageName);

        // $image = Image::make($request->file('profile'))
        //     ->fit(452, 452, function ($constraint) {
        //         $constraint->aspectRatio();
        //         $constraint->upsize();
        //     });

        // Storage::putFileAs('profile', (string)$image->encode('png', 95), $image_name);

        return response()->json([
            'img' => $request->hasFile('profile')
        ]);
    }
}
