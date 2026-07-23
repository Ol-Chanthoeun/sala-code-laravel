<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Test;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $tests = Test::orderBy('id', 'asc')->get();
        return view('admin.tests.index', compact('tests'));
    }

    public function create()
    {
        return view('admin.tests.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_name' => 'required',
            'question' => 'required',
            'option_a' => 'required',
            'option_b' => 'required',
            'option_c' => 'required',
            'option_d' => 'required',
            'correct_answer' => 'required',
        ]);

        Test::create($request->only([
            'course_name',
            'question',
            'option_a',
            'option_b',
            'option_c',
            'option_d',
            'correct_answer',
        ]));

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'បានបង្កើតតេស្តដោយជោគជ័យ!');
    }

    public function edit(Test $test)
    {
        return view('admin.tests.edit', compact('test'));
    }

    public function update(Request $request, Test $test)
    {
        $request->validate([
            'course_name' => 'required',
            'question' => 'required',
            'option_a' => 'required',
            'option_b' => 'required',
            'option_c' => 'required',
            'option_d' => 'required',
            'correct_answer' => 'required',
        ]);

        $test->update($request->only([
            'course_name',
            'question',
            'option_a',
            'option_b',
            'option_c',
            'option_d',
            'correct_answer',
        ]));

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'បានកែសម្រួលតេស្តដោយជោគជ័យ!');
    }

    public function destroy(Test $test)
    {
        $test->delete();

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'បានលុបតេស្តដោយជោគជ័យ!');
    }
}
