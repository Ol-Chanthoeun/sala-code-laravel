<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Lesson;
use App\Models\LessonExample;
use App\Models\Quiz;
use App\Models\Video;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalContacts = Contact::count();
        $totalUsers = User::count();
        $totalAdmins = User::whereIn('role', [User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN])->count();
        $totalCourses = Course::count();
        $totalSections = CourseSection::count();
        $totalLessons = Lesson::count();
        $totalExamples = LessonExample::count();
        $totalVideos = Video::count();
        $totalTests = Quiz::count();

        $recentContacts = Contact::latest()->take(5)->get();
        $recentUsers = User::latest()->take(5)->get();
        $recentCourses = Course::latest()->take(5)->get();
        $recentLessons = Lesson::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalContacts',
            'totalUsers',
            'totalAdmins',
            'totalCourses',
            'totalSections',
            'totalLessons',
            'totalExamples',
            'totalVideos',
            'totalTests',
            'recentContacts',
            'recentUsers',
            'recentCourses',
            'recentLessons'
        ));
    }

    public function contacts()
    {
        $contacts = Contact::orderBy('id', 'asc')->get();

        return view('admin.contacts', compact('contacts'));
    }

    public function deleteContact(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('admin.contacts')
            ->with('success', 'Contact message deleted successfully!');
    }
}
