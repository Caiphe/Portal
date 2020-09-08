<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
	public function index()
	{
		return view('templates.contact.index');
	}

	public function send(ContactRequest $request)
	{
		$validated = $request->validated();

		if ($validated['username'] !== null) {
			return redirect()->back();
		}

		Mail::to(env('MAIL_TO_ADDRESS'))
			->send(new ContactMail($validated));

		\Session::flash('alert', 'Success:Thank you for contacting us');

		return redirect()->back();
	}
}
