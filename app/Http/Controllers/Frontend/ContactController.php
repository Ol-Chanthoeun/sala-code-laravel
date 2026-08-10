<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        if (! $request->user()) {
            $request->session()->put('url.intended', route('contact'));
        }

        return view('frontend.contact');
    }

    public function store(Request $request)
    {
        $request->merge([
            'name' => trim(strip_tags((string) $request->user()->name)),
            'email' => trim(strip_tags((string) $request->user()->email)),
            'message' => trim(strip_tags((string) $request->input('message'))),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        Contact::create($validated);

        return redirect()
            ->back()
            ->with('success', 'Your message has been sent successfully.');
    }
}
