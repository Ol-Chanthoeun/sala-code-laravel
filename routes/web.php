<?php

use Illuminate\Support\Facades\Route;
use App\Models\Course;
use App\Models\Video;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\CourseLearningController;
use App\Http\Controllers\Frontend\QuizController as FrontendQuizController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\CourseSectionController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Admin\LessonExampleController;
use App\Http\Controllers\Admin\LmsQuizController;
use App\Http\Controllers\Admin\ProgrammingLanguageController;
use App\Http\Controllers\Admin\QuizCategoryController;
use App\Http\Controllers\Admin\QuizQuestionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\SystemSettingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Profile\ProfileController;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/

Route::view('/', 'frontend.home')->name('home');

Route::view('/about', 'frontend.about')->name('about');

Route::get('/courses', function () {
    $courses = Course::withCount(['lessons' => function ($query): void {
            $query->where('status', 'published');
        }])
        ->where('status', 'published')
        ->orderBy('id', 'asc')
        ->get();

    return view('frontend.courses', compact('courses'));
})->name('courses');

Route::get('/courses/{course:slug}', [CourseLearningController::class, 'showCourse'])
    ->name('courses.show');

Route::get('/courses/{course:slug}/learn', [CourseLearningController::class, 'start'])
    ->name('courses.learn');

Route::get('/courses/{course:slug}/lessons/{lesson:slug}', [CourseLearningController::class, 'showLesson'])
    ->name('courses.lessons.show');

Route::get('/videos', function () {
    $videos = Video::orderBy('id', 'asc')->get();

    return view('frontend.videos', compact('videos'));
})->name('videos');

Route::get('/test', [FrontendQuizController::class, 'index'])->name('test');
Route::get('/quiz/{language}', [FrontendQuizController::class, 'language'])->name('quiz.course');
Route::post('/quiz/{quiz}/start', [FrontendQuizController::class, 'start'])->name('quiz.start');
Route::get('/quiz-attempts/{attempt}', [FrontendQuizController::class, 'take'])->name('quiz.take');
Route::post('/quiz-attempts/{attempt}', [FrontendQuizController::class, 'submit'])->name('quiz.submit');
Route::get('/quiz-attempts/{attempt}/result', [FrontendQuizController::class, 'result'])->name('quiz.result');
Route::get('/quiz-attempts/{attempt}/review', [FrontendQuizController::class, 'review'])->name('quiz.review');
Route::get('/quiz-history', [FrontendQuizController::class, 'history'])->name('quiz.history');
Route::get('/quiz-attempts/{attempt}/certificate', [FrontendQuizController::class, 'certificate'])->name('quiz.certificate');
Route::get('/quizzes/{quiz}/leaderboard', [FrontendQuizController::class, 'leaderboard'])->name('quiz.leaderboard');

Route::view('/c-programming', 'frontend.c_programming')
    ->name('c_programming');

Route::get('/contact', [ContactController::class, 'index'])
    ->name('contact');

Route::post('/contact/store', [ContactController::class, 'store'])
    ->name('contact.store');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->middleware('system.feature:enable_registration')
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])->middleware('system.feature:enable_registration')
        ->name('register.post');

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.post');

    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->middleware('system.feature:enable_forgot_password')
        ->name('password.request');

    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->middleware('system.feature:enable_forgot_password')
        ->name('password.email');

    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])
        ->name('password.reset');

    Route::post('/reset-password', [AuthController::class, 'resetPassword'])
        ->name('password.update');

    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->middleware('system.feature:enable_google_login')
        ->name('google.redirect');

    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->middleware('system.feature:enable_google_login')
        ->name('google.callback');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile.show');

    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password.update');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,super_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/contacts', [DashboardController::class, 'contacts'])->name('contacts');
        Route::delete('/contacts/{contact}', [DashboardController::class, 'deleteContact'])->name('contacts.delete');

        Route::resource('users', UserController::class)->except(['create', 'store', 'show']);
        Route::patch('users/{user}/status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

        Route::resource('courses', CourseController::class);
        Route::resource('sections', CourseSectionController::class)->parameters(['sections' => 'section'])->except(['show']);
        Route::resource('lessons', LessonController::class);
        Route::resource('examples', LessonExampleController::class)->parameters(['examples' => 'example'])->except(['show']);
        Route::resource('videos', VideoController::class)->except(['show']);
        Route::resource('programming-languages', ProgrammingLanguageController::class)->except(['show']);
        Route::resource('quiz-categories', QuizCategoryController::class)->except(['show']);
        Route::resource('quizzes', LmsQuizController::class)->except(['show']);
        Route::resource('quiz-questions', QuizQuestionController::class)->except(['show']);

        Route::redirect('tests', 'quizzes')->name('tests.index');
    });

Route::middleware(['auth', 'role:super_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('admins', AdminManagementController::class)
            ->parameters(['admins' => 'admin'])
            ->except(['show']);

        Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])
            ->name('users.update-role');

        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::get('/activity-logs/{activityLog}', [ActivityLogController::class, 'show'])->name('activity-logs.show');
        Route::delete('/activity-logs', [ActivityLogController::class, 'destroySelected'])->name('activity-logs.destroy-selected');
        Route::delete('/activity-logs/clear/all', [ActivityLogController::class, 'clear'])->name('activity-logs.clear');

        Route::get('/system-settings', [SystemSettingController::class, 'index'])->name('system-settings.index');
        Route::put('/system-settings', [SystemSettingController::class, 'update'])->name('system-settings.update');
        Route::delete('/system-settings/reset', [SystemSettingController::class, 'reset'])->name('system-settings.reset');
    });
