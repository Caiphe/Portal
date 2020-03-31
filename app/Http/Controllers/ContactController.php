<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Shows contact page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

	function sendMail(Request $request)
	{
		$this->validate($request, [
		'first_name'     =>  'required',
		'last_name'     =>  'required',
		'email'  =>  'required|email',
		'message' =>  'required'
		]);

		$data = array(
			'first_name'      =>  $request->first_name,
			'last_name'      =>  $request->last_name,
			'email'      =>  $request->email,
			'message'   =>   $request->message
		);

		return back()->with('success', 'Thanks for contacting us!');

	}
}
