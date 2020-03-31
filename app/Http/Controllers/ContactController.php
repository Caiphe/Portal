<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactForm;

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
		'first_name' =>  'required',
		'last_name'  =>  'required',
		'email'		 =>  'required|email',
		'message' 	 =>  'required'
		]);

		$data = array(
			'first_name' =>  $request->first_name,
			'last_name'	 =>  $request->last_name,
			'email'      =>  $request->email,
			'message'    =>  $request->message
		);

		Mail::to(config('mail.from.address'))->send(new ContactForm($data));
		\Session::flash('alert', 'Success:Thank you for contacting us');

		return redirect()->back();
	}
}
