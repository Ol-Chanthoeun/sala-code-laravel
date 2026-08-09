<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportsExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_preview_users_but_cannot_access_restricted_reports(): void
    {
        $admin=User::factory()->create(['role'=>User::ROLE_ADMIN]);
        $student=User::factory()->create(['role'=>User::ROLE_USER,'name'=>'Report Student']);
        User::factory()->create(['role'=>User::ROLE_ADMIN,'name'=>'Hidden Admin']);

        $this->actingAs($admin)->get(route('admin.reports.index',['report'=>'users','preview'=>1]))
            ->assertOk()->assertSee($student->name)->assertSee('Total Records');
        $this->actingAs($admin)->get(route('admin.reports.index',['report'=>'admins','preview'=>1]))->assertForbidden();
        $this->actingAs($admin)->get(route('admin.reports.export',['report'=>'activity-logs','format'=>'csv']))->assertForbidden();
    }

    public function test_super_admin_exports_filtered_utf8_csv_and_records_event(): void
    {
        $super=User::factory()->create(['role'=>User::ROLE_SUPER_ADMIN]);
        User::factory()->create(['name'=>'សិស្ស សាលាកូដ','role'=>User::ROLE_USER,'status'=>User::STATUS_ACTIVE]);
        User::factory()->create(['name'=>'Inactive User','role'=>User::ROLE_USER,'status'=>User::STATUS_INACTIVE]);

        $response=$this->actingAs($super)->get(route('admin.reports.export',['report'=>'users','format'=>'csv','status'=>'active']));
        $response->assertOk()->assertDownload('users-report-'.now()->format('Y-m-d').'.csv');
        $csv=$response->streamedContent();
        $this->assertStringStartsWith("\xEF\xBB\xBF",$csv);
        $this->assertStringContainsString('សិស្ស សាលាកូដ',$csv);
        $this->assertStringNotContainsString('Inactive User',$csv);
        $this->assertStringNotContainsString('password',$csv);
        $this->assertDatabaseHas('activity_logs',['user_id'=>$super->id,'action'=>'Export CSV','module'=>'Reports']);
    }

    public function test_xlsx_and_pdf_are_real_downloadable_documents(): void
    {
        $super=User::factory()->create(['role'=>User::ROLE_SUPER_ADMIN]);
        User::factory()->create(['role'=>User::ROLE_USER]);

        $xlsx=$this->actingAs($super)->get(route('admin.reports.export',['report'=>'users','format'=>'xlsx']));
        $xlsx->assertOk()->assertDownload('users-report-'.now()->format('Y-m-d').'.xlsx');
        $this->assertStringStartsWith('PK',$xlsx->getContent());

        $pdf=$this->actingAs($super)->get(route('admin.reports.export',['report'=>'users','format'=>'pdf']));
        $pdf->assertOk()->assertDownload('users-report-'.now()->format('Y-m-d').'.pdf');
        $this->assertStringStartsWith('%PDF',$pdf->getContent());
        $this->assertSame(2,ActivityLog::where('module','Reports')->where('action','like','Export %')->count());
    }

    public function test_super_admin_can_preview_every_supported_report_type(): void
    {
        $super=User::factory()->create(['role'=>User::ROLE_SUPER_ADMIN]);
        foreach (['users','admins','courses','course-sections','lessons','code-examples','videos','video-playlists','quizzes','quiz-results','contact-messages','activity-logs'] as $type) {
            $this->actingAs($super)->get(route('admin.reports.index',['report'=>$type,'preview'=>1]))->assertOk();
        }
    }

    public function test_report_filters_are_contextual_and_ranges_are_validated(): void
    {
        $super=User::factory()->create(['role'=>User::ROLE_SUPER_ADMIN]);

        $this->actingAs($super)->get(route('admin.reports.index',[
            'report'=>'users','preview'=>1,'course_id'=>999999,
        ]))->assertOk()->assertDontSee('Course Id:');

        $this->actingAs($super)->get(route('admin.reports.index',[
            'report'=>'quiz-results','preview'=>1,'score_min'=>90,'score_max'=>20,
        ]))->assertSessionHasErrors('score_max');

        $this->actingAs($super)->get(route('admin.reports.index',[
            'report'=>'activity-logs','preview'=>1,'date_from'=>'2026-08-10','date_to'=>'2026-08-09',
        ]))->assertSessionHasErrors('date_to');
    }

    public function test_missing_activity_log_values_export_as_dashes(): void
    {
        $super=User::factory()->create(['role'=>User::ROLE_SUPER_ADMIN]);
        ActivityLog::create(['action'=>'View','module'=>'Reports','description'=>'No client metadata','browser'=>null,'ip_address'=>null]);

        $response=$this->actingAs($super)->get(route('admin.reports.export',['report'=>'activity-logs','format'=>'csv']));
        $response->assertOk();
        $csv=$response->streamedContent();
        $this->assertStringContainsString('No client metadata',$csv);
        $this->assertStringContainsString(',-,-,',$csv);
    }
}
