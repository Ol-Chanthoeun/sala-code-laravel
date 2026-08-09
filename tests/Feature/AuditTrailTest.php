<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Course;
use App\Services\ActivityLogService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditTrailTest extends TestCase
{
    use RefreshDatabase;

    public function test_failed_logins_capture_actor_email_ip_device_and_escalate_risk(): void
    {
        User::factory()->create(['email'=>'learner@example.com','password'=>'correct-password']);
        for($i=0;$i<4;$i++) $this->withHeader('User-Agent','Mozilla/5.0 (Windows NT 10.0) Chrome/120.0')->post(route('login.post'),['email'=>'learner@example.com','password'=>'wrong-password']);

        $log=ActivityLog::where('action','Failed Login')->latest('id')->firstOrFail();
        $this->assertSame('learner@example.com',$log->user_email);
        $this->assertSame('Google Chrome',$log->browser);
        $this->assertSame('Windows Desktop',$log->device);
        $this->assertSame('suspicious',$log->severity);
    }

    public function test_permission_denied_attempt_is_audited_and_normal_admin_stays_forbidden(): void
    {
        $admin=User::factory()->create(['role'=>User::ROLE_ADMIN]);
        $this->actingAs($admin)->get(route('admin.activity-logs.index'))->assertForbidden();
        $this->assertDatabaseHas('activity_logs',['user_id'=>$admin->id,'action'=>'Permission Denied','module'=>'Security']);
    }

    public function test_role_change_has_target_and_before_after_without_secrets(): void
    {
        $super=User::factory()->create(['role'=>User::ROLE_SUPER_ADMIN]);
        $user=User::factory()->create(['role'=>User::ROLE_USER]);
        $this->actingAs($super)->patch(route('admin.users.update-role',$user),['role'=>User::ROLE_ADMIN])->assertRedirect();

        $log=ActivityLog::where('action','Role Change')->firstOrFail();
        $this->assertSame($user->id,$log->target_id);
        $this->assertSame(['role'=>User::ROLE_USER],$log->old_values);
        $this->assertSame(['role'=>User::ROLE_ADMIN],$log->new_values);
        $this->assertArrayNotHasKey('password',$log->new_values ?? []);
    }

    public function test_sensitive_values_are_removed_recursively(): void
    {
        $clean=ActivityLogService::sanitize(['name'=>'Safe','password'=>'secret','nested'=>['access_token'=>'token','status'=>'active']]);
        $this->assertSame(['name'=>'Safe','nested'=>['status'=>'active']],$clean);
    }

    public function test_generic_crud_audit_captures_course_target_and_changed_values(): void
    {
        $admin=User::factory()->create(['role'=>User::ROLE_ADMIN]);
        $course=Course::create(['title'=>'Old Course','slug'=>'old-course','programming_language'=>'C','difficulty_level'=>'Beginner','status'=>'draft','price'=>'Free']);

        $this->actingAs($admin)->put(route('admin.courses.update',$course),[
            'title'=>'C Programming','slug'=>'old-course','short_description'=>'Updated','full_description'=>'Updated course',
            'programming_language'=>'C','difficulty_level'=>'Beginner','status'=>'published','price'=>'Free',
        ])->assertRedirect();

        $log=ActivityLog::where('module','Courses')->where('action','Publish')->latest('id')->firstOrFail();
        $this->assertStringContainsString("published course 'C Programming'", $log->description);
        $this->assertSame($course->id,$log->target_id);
        $this->assertSame('Old Course',$log->old_values['title']);
        $this->assertSame('C Programming',$log->new_values['title']);
        $this->assertSame('draft',$log->old_values['status']);
        $this->assertSame('published',$log->new_values['status']);
    }

    public function test_logs_are_append_only_and_clear_requires_typed_confirmation(): void
    {
        $super=User::factory()->create(['role'=>User::ROLE_SUPER_ADMIN]);
        $log=ActivityLog::create(['action'=>'Update','module'=>'Courses','description'=>'Changed course']);
        $this->actingAs($super)->delete(route('admin.activity-logs.destroy-selected'),['log_ids'=>[$log->id]])->assertStatus(405);
        $this->assertDatabaseHas('activity_logs',['id'=>$log->id]);

        $this->actingAs($super)->delete(route('admin.activity-logs.clear'),['confirmation'=>'wrong'])->assertSessionHasErrors('confirmation');
        $this->assertDatabaseHas('activity_logs',['id'=>$log->id]);
    }
}
