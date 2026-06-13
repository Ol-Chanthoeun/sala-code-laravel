<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function contacts()
    {
        $contacts = Contact::latest()->get();

        return view('admin.contacts', compact('contacts'));
    }
}