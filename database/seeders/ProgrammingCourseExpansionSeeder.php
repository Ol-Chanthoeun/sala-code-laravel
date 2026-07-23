<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Lesson;
use App\Models\LessonExample;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProgrammingCourseExpansionSeeder extends Seeder
{
    public function run(): void
    {
        $creator = User::where('role', User::ROLE_SUPER_ADMIN)->first() ?? User::first();

        foreach ($this->courses() as $courseData) {
            $course = Course::where('slug', $courseData['slug'])
                ->orWhere('programming_language', $courseData['language'])
                ->orWhere('title', 'like', '%' . $courseData['keyword'] . '%')
                ->first() ?? new Course();

            $course->fill([
                'title' => $courseData['title'],
                'slug' => $courseData['slug'],
                'short_description' => "វគ្គសិក្សា {$courseData['label']} ពេញលេញពីកម្រិតដំបូងដល់កម្រិតខ្ពស់។",
                'description' => "រៀន {$courseData['label']} ជាភាសាខ្មែរ ជាមួយមេរៀន កូដ ឧទាហរណ៍ លំហាត់ និងសំណួរសាកល្បង។",
                'full_description' => "វគ្គនេះរៀបចំជា ២០ ផ្នែក និង ១០០ មេរៀន ដើម្បីជួយអ្នករៀន {$courseData['label']} ពីមូលដ្ឋាន ការអនុវត្ត ការរកកំហុស រហូតដល់គម្រោងតូចៗ និងប្រឡងបញ្ចប់វគ្គ។ មាតិកាទាំងអស់រក្សាទុកក្នុង database ហើយ Admin អាចកែ បន្ថែម លុប ប្ដូរលំដាប់ និង publish/unpublish បាន។",
                'programming_language' => $courseData['language'],
                'difficulty_level' => 'Beginner',
                'status' => 'published',
                'created_by' => $course->created_by ?: $creator?->id,
                'price' => 'Free',
            ]);
            $course->save();

            $course->lessons()->delete();
            $course->sections()->delete();

            foreach ($this->sections($courseData['label']) as $sectionData) {
                $section = CourseSection::create([
                    'course_id' => $course->id,
                    'title' => $sectionData['title'],
                    'description' => $sectionData['description'],
                    'order_number' => $sectionData['order'],
                ]);

                foreach ($sectionData['lessons'] as $lessonIndex => $lessonTitle) {
                    $lessonOrder = $lessonIndex + 1;
                    $lesson = Lesson::create($this->lessonPayload(
                        course: $course,
                        section: $section,
                        creator: $creator,
                        courseData: $courseData,
                        sectionTitle: $sectionData['title'],
                        sectionOrder: $sectionData['order'],
                        lessonOrder: $lessonOrder,
                        lessonTitle: $lessonTitle
                    ));

                    LessonExample::create([
                        'lesson_id' => $lesson->id,
                        'title' => 'ឧទាហរណ៍សម្រាប់ ' . $lessonTitle,
                        'source_code' => $lesson->source_code,
                        'expected_output' => $lesson->expected_output,
                        'explanation' => $lesson->code_explanation,
                    ]);
                }
            }
        }
    }

    /**
     * @return array<int, array{slug:string,title:string,label:string,language:string,keyword:string,code_key:string}>
     */
    private function courses(): array
    {
        return [
            ['slug' => 'cpp-programming', 'title' => 'ថ្នាក់ C++', 'label' => 'ភាសា C++', 'language' => 'C++', 'keyword' => 'C++', 'code_key' => 'cpp'],
            ['slug' => 'python-programming', 'title' => 'ថ្នាក់ Python', 'label' => 'ភាសា Python', 'language' => 'Python', 'keyword' => 'Python', 'code_key' => 'python'],
            ['slug' => 'java-programming', 'title' => 'ថ្នាក់ Java', 'label' => 'ភាសា Java', 'language' => 'Java', 'keyword' => 'Java', 'code_key' => 'java'],
            ['slug' => 'html-course', 'title' => 'ថ្នាក់ HTML', 'label' => 'HTML', 'language' => 'HTML', 'keyword' => 'HTML', 'code_key' => 'html'],
            ['slug' => 'css-course', 'title' => 'ថ្នាក់ CSS', 'label' => 'CSS', 'language' => 'CSS', 'keyword' => 'CSS', 'code_key' => 'css'],
            ['slug' => 'javascript-programming', 'title' => 'ថ្នាក់ JavaScript', 'label' => 'ភាសា JavaScript', 'language' => 'JavaScript', 'keyword' => 'JavaScript', 'code_key' => 'javascript'],
            ['slug' => 'git-course', 'title' => 'ថ្នាក់ Git', 'label' => 'Git', 'language' => 'Git', 'keyword' => 'Git', 'code_key' => 'git'],
            ['slug' => 'github-course', 'title' => 'ថ្នាក់ GitHub', 'label' => 'GitHub', 'language' => 'GitHub', 'keyword' => 'GitHub', 'code_key' => 'github'],
        ];
    }

    /**
     * @return array<int, array{order:int,title:string,description:string,lessons:array<int,string>}>
     */
    private function sections(string $label): array
    {
        return [
            ['order' => 1, 'title' => "ផ្នែកទី ១៖ ការណែនាំអំពី {$label}", 'description' => 'ស្គាល់គោលបំណង ប្រវត្តិ ឧបករណ៍ និងរបៀបចាប់ផ្ដើម។', 'lessons' => ["តើ {$label} ជាអ្វី?", "ប្រវត្តិ និងគោលបំណងរបស់ {$label}", "អត្ថប្រយោជន៍នៃការរៀន {$label}", 'ការដំឡើងឧបករណ៍ចាំបាច់', 'កម្មវិធីដំបូងសម្រាប់អ្នកចាប់ផ្ដើម']],
            ['order' => 2, 'title' => 'ផ្នែកទី ២៖ មូលដ្ឋានសរសេរកូដ', 'description' => 'យល់ពីរចនាសម្ព័ន្ធ syntax comment និងរបៀបដំណើរការកូដ។', 'lessons' => ['រចនាសម្ព័ន្ធកូដមូលដ្ឋាន', 'ការសរសេរ comment', 'ការដាក់ឈ្មោះឲ្យមានន័យ', 'ការរៀបចំឯកសារកូដ', 'ការដំណើរការកម្មវិធី']],
            ['order' => 3, 'title' => 'ផ្នែកទី ៣៖ អថេរ និងតម្លៃ', 'description' => 'រៀនរក្សាទុកតម្លៃ ប្រភេទទិន្នន័យ និងការបម្លែងតម្លៃ។', 'lessons' => ['អថេរ និងតម្លៃ', 'ប្រភេទទិន្នន័យសំខាន់ៗ', 'តម្លៃថេរ', 'ការបម្លែងប្រភេទទិន្នន័យ', 'វិសាលភាពអថេរ']],
            ['order' => 4, 'title' => 'ផ្នែកទី ៤៖ ការបញ្ចូល និងបង្ហាញទិន្នន័យ', 'description' => 'បង្ហាញលទ្ធផល ទទួលទិន្នន័យ និងរៀបចំទ្រង់ទ្រាយ output។', 'lessons' => ['ការបង្ហាញលទ្ធផល', 'ការទទួលទិន្នន័យពីអ្នកប្រើ', 'ការរៀបចំទ្រង់ទ្រាយអត្ថបទ', 'ការប្រើ escape characters', 'ការបង្ហាញលទ្ធផលជាច្រើនបន្ទាត់']],
            ['order' => 5, 'title' => 'ផ្នែកទី ៥៖ សញ្ញាប្រមាណវិធី', 'description' => 'រៀនប្រើសញ្ញាគណនា ប្រៀបធៀប logic និងលំដាប់អាទិភាព។', 'lessons' => ['សញ្ញាគណិតវិទ្យា', 'សញ្ញាផ្តល់តម្លៃ', 'សញ្ញាប្រៀបធៀប', 'សញ្ញាតក្កវិជ្ជា', 'លំដាប់អាទិភាពសញ្ញា']],
            ['order' => 6, 'title' => 'ផ្នែកទី ៦៖ ការសម្រេចចិត្ត', 'description' => 'ធ្វើឲ្យកម្មវិធីជ្រើសរើសផ្លូវដំណើរការផ្សេងៗតាមលក្ខខណ្ឌ។', 'lessons' => ['លក្ខខណ្ឌមូលដ្ឋាន', 'លក្ខខណ្ឌមានជម្រើសពីរ', 'លក្ខខណ្ឌច្រើនជម្រើស', 'លក្ខខណ្ឌជាន់គ្នា', 'ការជ្រើសរើសករណី']],
            ['order' => 7, 'title' => 'ផ្នែកទី ៧៖ រង្វិលជុំ', 'description' => 'ធ្វើការដដែលៗដោយប្រើ loop និងគ្រប់គ្រងការបន្តឬបញ្ឈប់។', 'lessons' => ['រង្វិលជុំមូលដ្ឋាន', 'រង្វិលជុំមានចំនួនកំណត់', 'រង្វិលជុំតាមលក្ខខណ្ឌ', 'រង្វិលជុំជាន់គ្នា', 'ការបន្ត និងបញ្ឈប់រង្វិលជុំ']],
            ['order' => 8, 'title' => 'ផ្នែកទី ៨៖ មុខងារ និងការបំបែកកូដ', 'description' => 'បង្កើតមុខងារ បញ្ជូនតម្លៃ និងរៀបចំកូដឲ្យងាយថែទាំ។', 'lessons' => ['សេចក្ដីណែនាំអំពីមុខងារ', 'ការបង្កើតមុខងារ', 'ប៉ារ៉ាម៉ែត្រ និងតម្លៃត្រឡប់', 'មុខងារហៅមុខងារផ្សេង', 'ការរៀបចំមុខងារឲ្យស្អាត']],
            ['order' => 9, 'title' => 'ផ្នែកទី ៩៖ បញ្ជី និងទិន្នន័យជាក្រុម', 'description' => 'គ្រប់គ្រងទិន្នន័យច្រើនធាតុក្នុង collection ឬ data structure។', 'lessons' => ['បញ្ជីទិន្នន័យមូលដ្ឋាន', 'ការបន្ថែម និងលុបទិន្នន័យ', 'ការរត់កាត់ទិន្នន័យ', 'ការស្វែងរកទិន្នន័យ', 'ការរៀបចំទិន្នន័យ']],
            ['order' => 10, 'title' => 'ផ្នែកទី ១០៖ អត្ថបទ និងខ្សែអក្សរ', 'description' => 'ធ្វើការជាមួយអត្ថបទ ការភ្ជាប់ ការបែងចែក និងការប្រៀបធៀប។', 'lessons' => ['អត្ថបទមូលដ្ឋាន', 'ការភ្ជាប់អត្ថបទ', 'ការកាត់អត្ថបទ', 'ការប្រៀបធៀបអត្ថបទ', 'ការសម្អាត និងបម្លែងអត្ថបទ']],
            ['order' => 11, 'title' => 'ផ្នែកទី ១១៖ ការរៀបចំទិន្នន័យកម្រិតមធ្យម', 'description' => 'យល់ពី object record map និងទិន្នន័យដែលមានរចនាសម្ព័ន្ធ។', 'lessons' => ['ទិន្នន័យមានរចនាសម្ព័ន្ធ', 'គន្លឹះ និងតម្លៃ', 'ការរួមបញ្ចូលទិន្នន័យ', 'ការបញ្ជូនទិន្នន័យទៅមុខងារ', 'ការប្រើទិន្នន័យក្នុងគម្រោង']],
            ['order' => 12, 'title' => 'ផ្នែកទី ១២៖ ការគ្រប់គ្រងឯកសារ', 'description' => 'អាន សរសេរ និងរក្សាទុកទិន្នន័យក្នុងឯកសារ ឬប្រភពខាងក្រៅ។', 'lessons' => ['ការអានឯកសារ', 'ការសរសេរឯកសារ', 'ការបន្ថែមទិន្នន័យទៅឯកសារ', 'ការពិនិត្យកំហុសឯកសារ', 'គម្រោងរក្សាទុកទិន្នន័យ']],
            ['order' => 13, 'title' => 'ផ្នែកទី ១៣៖ កំហុស និងការរកកំហុស', 'description' => 'ស្គាល់ប្រភេទកំហុស និងរៀនរកកំហុសដោយមានវិធីសាស្ត្រ។', 'lessons' => ['កំហុស syntax', 'កំហុសពេលដំណើរការ', 'កំហុសតក្កវិជ្ជា', 'ការប្រើសារ debug', 'ការកែបញ្ហាជាជំហានៗ']],
            ['order' => 14, 'title' => 'ផ្នែកទី ១៤៖ ការគិតបែបកម្មវិធី', 'description' => 'បំបែកបញ្ហា រចនាលំដាប់ជំហាន និងសរសេរ algorithm។', 'lessons' => ['ការបំបែកបញ្ហា', 'ការរចនាលំដាប់ជំហាន', 'ការសរសេរ pseudo code', 'ការវាយតម្លៃលទ្ធផល', 'ការកែលម្អដំណោះស្រាយ']],
            ['order' => 15, 'title' => 'ផ្នែកទី ១៥៖ បណ្ណាល័យ និងឧបករណ៍ជំនួយ', 'description' => 'ប្រើមុខងាររួចរាល់ package library ឬ tool ដើម្បីធ្វើការបានលឿន។', 'lessons' => ['សេចក្ដីណែនាំអំពីបណ្ណាល័យ', 'ការនាំចូលមុខងារ', 'មុខងារដែលប្រើញឹកញាប់', 'ការអានឯកសារ reference', 'ការជ្រើសរើសឧបករណ៍សមស្រប']],
            ['order' => 16, 'title' => 'ផ្នែកទី ១៦៖ ការអនុវត្តល្អ', 'description' => 'រៀនសរសេរកូដស្អាត ងាយអាន ងាយថែទាំ និងមានសុវត្ថិភាព។', 'lessons' => ['ស្ទីលសរសេរកូដ', 'ការដាក់ឈ្មោះ', 'ការរៀបចំ folder', 'ការសរសេរ comment ឲ្យមានប្រយោជន៍', 'ការកាត់បន្ថយកូដស្ទួន']],
            ['order' => 17, 'title' => 'ផ្នែកទី ១៧៖ ការធ្វើតេស្ត', 'description' => 'សាកល្បងកូដ ពិនិត្យលទ្ធផល និងធ្វើឲ្យកម្មវិធីមានគុណភាព។', 'lessons' => ['មូលដ្ឋានការធ្វើតេស្ត', 'ការធ្វើតេស្ត input', 'ការធ្វើតេស្តករណីខុស', 'ការប្រៀបធៀប expected output', 'ការកត់ត្រាបញ្ហា']],
            ['order' => 18, 'title' => 'ផ្នែកទី ១៨៖ គម្រោងតូចៗ', 'description' => 'អនុវត្តចំណេះដឹងតាមរយៈគម្រោងតូចៗ។', 'lessons' => ['គម្រោងម៉ាស៊ីនគិតលេខ', 'គម្រោងបញ្ជីភារកិច្ច', 'គម្រោងគ្រប់គ្រងនិស្សិត', 'គម្រោងស្វែងរកទិន្នន័យ', 'គម្រោងចុងក្រោយតូច']],
            ['order' => 19, 'title' => 'ផ្នែកទី ១៩៖ លំហាត់អនុវត្ត', 'description' => 'ពង្រឹងសមត្ថភាពតាមរយៈសំណួរ កូដខ្វះ និងការស្វែងរកកំហុស។', 'lessons' => ['សំណួរជ្រើសរើសចម្លើយ', 'បំពេញចន្លោះទំនេរ', 'បំពេញកូដឲ្យពេញលេញ', 'ទាយលទ្ធផលកូដ', 'ស្វែងរកកំហុសក្នុងកូដ']],
            ['order' => 20, 'title' => 'ផ្នែកទី ២០៖ ប្រឡងបញ្ចប់វគ្គ', 'description' => 'វាស់ស្ទង់ចំណេះដឹងចុងក្រោយដោយទ្រឹស្តី កូដ និងកិច្ចការអនុវត្ត។', 'lessons' => ['ការត្រៀមប្រឡងទ្រឹស្តី', 'ការធ្វើតេស្តសរសេរកូដ', 'កិច្ចការអនុវត្តជាក់ស្តែង', 'ការពិនិត្យគម្រោងចុងក្រោយ', 'ផែនការរៀនបន្ត']],
        ];
    }

    /**
     * @param array{slug:string,title:string,label:string,language:string,keyword:string,code_key:string} $courseData
     * @return array<string, mixed>
     */
    private function lessonPayload(
        Course $course,
        CourseSection $section,
        ?User $creator,
        array $courseData,
        string $sectionTitle,
        int $sectionOrder,
        int $lessonOrder,
        string $lessonTitle
    ): array {
        $sample = $this->sourceCode($courseData['code_key'], $sectionOrder);
        $difficulty = $sectionOrder <= 7 ? 'Beginner' : ($sectionOrder <= 14 ? 'Intermediate' : 'Advanced');
        $minutes = $difficulty === 'Beginner' ? 18 : ($difficulty === 'Intermediate' ? 25 : 35);

        return [
            'course_id' => $course->id,
            'section_id' => $section->id,
            'title' => $lessonTitle,
            'slug' => sprintf('%s-section-%02d-lesson-%02d', $courseData['code_key'], $sectionOrder, $lessonOrder),
            'short_description' => "មេរៀននេះពន្យល់អំពី {$lessonTitle} សម្រាប់ {$courseData['label']} ដោយមានឧទាហរណ៍ ការពន្យល់កូដ និងលំហាត់។",
            'lesson_content' => $this->lessonContent($lessonTitle, $sectionTitle, $courseData['label']),
            'source_code' => $sample['code'],
            'expected_output' => $sample['output'],
            'explanation' => "ក្នុងមេរៀន {$lessonTitle} អ្នកនឹងរៀនគំនិតសំខាន់ៗតាមជំហាន។ សូមអាននិយមន័យ សាកល្បងកូដ ហើយកែតម្លៃខ្លះៗដើម្បីយល់ថាលទ្ធផលប្រែប្រួលយ៉ាងដូចម្តេច។",
            'code_explanation' => $this->codeExplanation($courseData['label'], $lessonTitle),
            'common_mistakes' => "កំហុសដែលជួបញឹកញាប់គឺភ្លេចសញ្ញាចាំបាច់ សរសេរឈ្មោះអថេរខុស មិនពិនិត្យលទ្ធផលបន្ទាប់ពីកែ និងចម្លងកូដដោយមិនយល់ពីមូលហេតុ។",
            'tips' => 'សូមសរសេរកូដបន្តិចម្តងៗ ដំណើរការសាកល្បងជាញឹកញាប់ ដាក់ឈ្មោះឲ្យមានន័យ និងកត់ត្រាកំហុសដែលអ្នកបានជួប។',
            'summary' => "សរុបមក {$lessonTitle} ជាមូលដ្ឋានសំខាន់ក្នុងការរៀន {$courseData['label']}។ បើអ្នកអាចពន្យល់កូដវិញដោយពាក្យខ្លួនឯង នោះមានន័យថាអ្នកយល់ល្អហើយ។",
            'exercise' => "លំហាត់៖ ១) ដំណើរការឧទាហរណ៍។ ២) ប្ដូរតម្លៃមួយចំនួន។ ៣) បន្ថែមលទ្ធផលបង្ហាញថ្មី។ ៤) សរសេរកូដតូចមួយដោយប្រើគំនិត {$lessonTitle}។",
            'quiz' => $this->quiz($courseData['label'], $lessonTitle),
            'difficulty_level' => $difficulty,
            'estimated_learning_time' => $minutes,
            'video_url' => $this->videoUrl($courseData['code_key']),
            'order_number' => $lessonOrder,
            'status' => 'published',
            'created_by' => $creator?->id,
        ];
    }

    private function lessonContent(string $lessonTitle, string $sectionTitle, string $label): string
    {
        return <<<HTML
<h2>{$lessonTitle}</h2>
<p>មេរៀននេះស្ថិតក្នុង {$sectionTitle}។ គោលបំណងគឺជួយឲ្យអ្នកយល់ពី {$lessonTitle} ក្នុង {$label} ដោយចាប់ផ្ដើមពីមូលដ្ឋាន បន្ទាប់មកអនុវត្តជាមួយឧទាហរណ៍។</p>
<p>សូមមើលកូដឧទាហរណ៍ខាងក្រោម ហើយព្យាយាមសរសេរឡើងវិញដោយខ្លួនឯង។ ការសរសេរឡើងវិញជួយឲ្យអ្នកចងចាំ syntax និងយល់លំហូររបស់កម្មវិធីកាន់តែល្អ។</p>
<p>បន្ទាប់ពីដំណើរការកូដ សូមប្រៀបធៀបលទ្ធផលជាមួយ Expected Output ហើយសាកល្បងប្ដូរតម្លៃ ដើម្បីមើលការផ្លាស់ប្ដូរលទ្ធផល។</p>
HTML;
    }

    private function codeExplanation(string $label, string $lessonTitle): string
    {
        return "កូដនេះបង្ហាញគំនិត {$lessonTitle} ក្នុង {$label}។ ផ្នែកដំបូងរៀបចំបរិស្ថាន ឬតម្លៃចាំបាច់។ ផ្នែកកណ្តាលអនុវត្ត logic សំខាន់។ ផ្នែកចុងក្រោយបង្ហាញលទ្ធផល ដើម្បីឲ្យអ្នកអាចពិនិត្យថាកម្មវិធីដំណើរការត្រឹមត្រូវ។";
    }

    /**
     * @return array{code:string,output:string}
     */
    private function sourceCode(string $key, int $sectionOrder): array
    {
        return match ($key) {
            'cpp' => ['code' => <<<'CODE'
#include <iostream>
using namespace std;

int main() {
    int score = 90;
    cout << "Sala Code C++ score: " << score << endl;
    return 0;
}
CODE, 'output' => 'Sala Code C++ score: 90'],
            'python' => ['code' => <<<'CODE'
score = 90
name = "Sala Code"

print(f"{name} Python score: {score}")
CODE, 'output' => 'Sala Code Python score: 90'],
            'java' => ['code' => <<<'CODE'
public class Main {
    public static void main(String[] args) {
        int score = 90;
        System.out.println("Sala Code Java score: " + score);
    }
}
CODE, 'output' => 'Sala Code Java score: 90'],
            'html' => ['code' => <<<'CODE'
<!DOCTYPE html>
<html>
<head>
    <title>Sala Code</title>
</head>
<body>
    <h1>Hello HTML</h1>
    <p>Learn with Sala Code</p>
</body>
</html>
CODE, 'output' => 'A web page with the heading "Hello HTML" and a paragraph.'],
            'css' => ['code' => <<<'CODE'
body {
    font-family: Arial, sans-serif;
    background: #f8fbff;
}

h1 {
    color: #1f6fe5;
}
CODE, 'output' => 'The page uses a light background and blue heading text.'],
            'javascript' => ['code' => <<<'CODE'
const score = 90;
const name = "Sala Code";

console.log(`${name} JavaScript score: ${score}`);
CODE, 'output' => 'Sala Code JavaScript score: 90'],
            'git' => ['code' => <<<'CODE'
git init
git add .
git commit -m "first lesson"
git status
CODE, 'output' => 'Git creates a repository, stages files, commits changes, and shows status.'],
            'github' => ['code' => <<<'CODE'
git remote add origin https://github.com/username/sala-code.git
git branch -M main
git push -u origin main
CODE, 'output' => 'The local project is connected and pushed to GitHub.'],
            default => ['code' => 'print("Sala Code")', 'output' => 'Sala Code'],
        };
    }

    /**
     * @return array<int, array{question:string,options:array<int,string>,answer:string}>
     */
    private function quiz(string $label, string $lessonTitle): array
    {
        return [
            [
                'question' => "តើមេរៀន {$lessonTitle} ជួយអ្វីក្នុងការរៀន {$label}?",
                'options' => ['ជួយយល់គំនិត និងអនុវត្ត', 'ជួយលុប project', 'ជួយបិទកុំព្យូទ័រ', 'ជួយប្តូររូបភាពប៉ុណ្ណោះ'],
                'answer' => 'ជួយយល់គំនិត និងអនុវត្ត',
            ],
            [
                'question' => 'តើអ្វីជាវិធីរៀនកូដដែលល្អ?',
                'options' => ['សរសេរឡើងវិញ និងសាកល្បង', 'ចម្លងដោយមិនអាន', 'រំលងលំហាត់', 'មើលតែចំណងជើង'],
                'answer' => 'សរសេរឡើងវិញ និងសាកល្បង',
            ],
            [
                'question' => 'តើហេតុអ្វីត្រូវប្រៀបធៀប Expected Output?',
                'options' => ['ដើម្បីពិនិត្យលទ្ធផលត្រឹមត្រូវ', 'ដើម្បីលាក់កំហុស', 'ដើម្បីលុបកូដ', 'ដើម្បីប្តូរ font'],
                'answer' => 'ដើម្បីពិនិត្យលទ្ធផលត្រឹមត្រូវ',
            ],
            [
                'question' => 'តើអ្នកគួរធ្វើអ្វីបន្ទាប់ពីយល់ឧទាហរណ៍?',
                'options' => ['កែតម្លៃ និងបង្កើតឧទាហរណ៍ថ្មី', 'បិទមេរៀនភ្លាម', 'លុបឯកសារ', 'មិនចាំបាច់អនុវត្ត'],
                'answer' => 'កែតម្លៃ និងបង្កើតឧទាហរណ៍ថ្មី',
            ],
        ];
    }

    private function videoUrl(string $key): string
    {
        return match ($key) {
            'cpp' => 'https://www.youtube.com/watch?v=vLnPwxZdW4Y',
            'python' => 'https://www.youtube.com/watch?v=_uQrJ0TkZlc',
            'java' => 'https://www.youtube.com/watch?v=eIrMbAQSU34',
            'html' => 'https://www.youtube.com/watch?v=pQN-pnXPaVg',
            'css' => 'https://www.youtube.com/watch?v=OXGznpKZ_sA',
            'javascript' => 'https://www.youtube.com/watch?v=PkZNo7MFNFg',
            'git' => 'https://www.youtube.com/watch?v=RGOj5yH7evk',
            'github' => 'https://www.youtube.com/watch?v=RGOj5yH7evk',
            default => 'https://www.youtube.com/watch?v=KJgsSFOSQv0',
        };
    }
}
