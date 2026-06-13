<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index()
    {
        return view('frontend.contact');
    }

    public function store(Request $request)
    {
        Contact::create([

            'name' => $request->name,

            'email' => $request->email,

            'message' => $request->message,

        ]);

        return redirect()
            ->back()
            ->with('success', 'Message sent successfully!');
    }
}