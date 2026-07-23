@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
    <div class="dashboard-cards">
        <div class="card">
            <div class="card-info">
                <h3>Total Users</h3>
                <div class="number">{{ $totalUsers }}</div>
                <a href="{{ route('admin.users.index') }}" class="view-all">View all</a>
            </div>
            <div class="card-icon"><i class="fas fa-users"></i></div>
        </div>

        <div class="card">
            <div class="card-info">
                <h3>Total Admins</h3>
                <div class="number">{{ $totalAdmins }}</div>
                @if(auth()->user()->isSuperAdmin())
                    <a href="{{ route('admin.admins.index') }}" class="view-all">View all</a>
                @endif
            </div>
            <div class="card-icon"><i class="fas fa-user-shield"></i></div>
        </div>

        <div class="card">
            <div class="card-info">
                <h3>Total Courses</h3>
                <div class="number">{{ $totalCourses }}</div>
                <a href="{{ route('admin.courses.index') }}" class="view-all">View all</a>
            </div>
            <div class="card-icon"><i class="fas fa-book"></i></div>
        </div>

        <div class="card">
            <div class="card-info">
                <h3>Total Lessons</h3>
                <div class="number">{{ $totalLessons }}</div>
                <a href="{{ route('admin.lessons.index') }}" class="view-all">View all</a>
            </div>
            <div class="card-icon"><i class="fas fa-file-code"></i></div>
        </div>
    </div>

    <div class="dashboard-cards">
        <div class="card">
            <div class="card-info">
                <h3>Total Sections</h3>
                <div class="number">{{ $totalSections }}</div>
                <a href="{{ route('admin.sections.index') }}" class="view-all">View all</a>
            </div>
            <div class="card-icon"><i class="fas fa-layer-group"></i></div>
        </div>

        <div class="card">
            <div class="card-info">
                <h3>Code Examples</h3>
                <div class="number">{{ $totalExamples }}</div>
                <a href="{{ route('admin.examples.index') }}" class="view-all">View all</a>
            </div>
            <div class="card-icon"><i class="fas fa-code"></i></div>
        </div>

        <div class="card">
            <div class="card-info">
                <h3>Total Videos</h3>
                <div class="number">{{ $totalVideos }}</div>
                <a href="{{ route('admin.videos.index') }}" class="view-all">View all</a>
            </div>
            <div class="card-icon"><i class="fas fa-video"></i></div>
        </div>

        <div class="card">
            <div class="card-info">
                <h3>Total Quizzes</h3>
                <div class="number">{{ $totalTests }}</div>
                <a href="{{ route('admin.quizzes.index') }}" class="view-all">View all</a>
            </div>
            <div class="card-icon"><i class="fas fa-tasks"></i></div>
        </div>
    </div>

    <div class="two-columns">
        <div class="data-table">
            <div class="table-header">
                <h3>Recently Registered Users</h3>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentUsers as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $user->role)) }}</td>
                                <td>{{ $user->created_at?->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center;">No users yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="data-table">
            <div class="table-header">
                <h3>Recently Added Lessons</h3>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Lesson</th>
                            <th>Course</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentLessons as $lesson)
                            <tr>
                                <td>{{ $lesson->title }}</td>
                                <td>{{ $lesson->course?->title }}</td>
                                <td>{{ ucfirst($lesson->status) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align:center;">No lessons yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="two-columns" style="margin-top:25px;">
        <div class="data-table">
            <div class="table-header">
                <h3>Recent Courses</h3>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Language</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentCourses as $course)
                            <tr>
                                <td>{{ $course->title }}</td>
                                <td>{{ $course->programming_language }}</td>
                                <td>{{ ucfirst($course->status) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align:center;">No courses yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="system-info">
            <div class="section-title">Quick Actions</div>
            <div class="quick-actions">
                <a href="{{ route('admin.courses.create') }}" class="action-btn">
                    <i class="fas fa-plus-circle"></i> Add Course
                </a>
                <a href="{{ route('admin.sections.create') }}" class="action-btn">
                    <i class="fas fa-plus-circle"></i> Add Section
                </a>
                <a href="{{ route('admin.lessons.create') }}" class="action-btn">
                    <i class="fas fa-plus-circle"></i> Add Lesson
                </a>
                <a href="{{ route('admin.quizzes.create') }}" class="action-btn">
                    <i class="fas fa-plus-circle"></i> Add Quiz
                </a>
            </div>
        </div>
    </div>
@endsection
