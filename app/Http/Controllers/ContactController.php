<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class ContactController extends Controller
{
    public function index()
    {
        return view('templates.contact.index');
    }

	public function send(ContactRequest $request)
	{
		$validated = $request->validated();

		Mail::to(env('MAIL_TO_ADDRESS'))
            ->send(new ContactMail($validated));

		\Session::flash('alert', 'Success:Thank you for contacting us');

		return redirect()->back();
	}
}
