<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Lesson;
use App\Models\LessonExample;
use App\Models\User;
use Illuminate\Database\Seeder;

class CProgrammingTestSeeder extends Seeder
{
    public function run(): void
    {
        $creator = User::where('role', User::ROLE_SUPER_ADMIN)->first() ?? User::first();

        $course = Course::updateOrCreate(
            ['slug' => 'c-programming'],
            [
                'title' => 'C Programming',
                'short_description' => 'វគ្គសិក្សាភាសា C ពេញលេញពីកម្រិតដំបូងរហូតដល់កម្រិតខ្ពស់។',
                'description' => 'រៀនភាសា C ជាភាសាខ្មែរ ជាមួយមេរៀន កូដ ឧទាហរណ៍ លំហាត់ និងសំណួរសាកល្បង។',
                'full_description' => 'វគ្គនេះរៀបចំជាជំហានៗសម្រាប់អ្នកចាប់ផ្ដើម និងអ្នកចង់ពង្រឹងមូលដ្ឋានភាសា C។ អ្នកនឹងរៀនពីការដំឡើងឧបករណ៍ សរសេរកូដដំបូង អថេរ ប្រភេទទិន្នន័យ Input/Output Operators Decision Making Loops Functions Arrays Strings Pointers Structures Memory File Handling Preprocessor Debugging Best Practices Mini Projects Exercises និង Final Exam។',
                'programming_language' => 'C',
                'difficulty_level' => 'Beginner to Advanced',
                'status' => 'published',
                'created_by' => $creator?->id,
                'price' => 'Free',
            ]
        );

        $course->lessons()->delete();
        $course->sections()->delete();

        foreach ($this->curriculum() as $sectionData) {
            $section = CourseSection::create([
                'course_id' => $course->id,
                'title' => $sectionData['title'],
                'description' => $sectionData['description'],
                'order_number' => $sectionData['order'],
            ]);

            foreach ($sectionData['lessons'] as $lessonIndex => $title) {
                $lessonOrder = $lessonIndex + 1;
                $lesson = Lesson::create($this->lessonPayload(
                    course: $course,
                    section: $section,
                    creator: $creator,
                    sectionOrder: $sectionData['order'],
                    lessonOrder: $lessonOrder,
                    title: $title,
                    sectionTitle: $sectionData['title']
                ));

                LessonExample::create([
                    'lesson_id' => $lesson->id,
                    'title' => 'ឧទាហរណ៍សម្រាប់ ' . $title,
                    'source_code' => $lesson->source_code,
                    'expected_output' => $lesson->expected_output,
                    'explanation' => $lesson->code_explanation,
                ]);
            }
        }
    }

    /**
     * @return array<int, array{order:int,title:string,description:string,lessons:array<int,string>}>
     */
    private function curriculum(): array
    {
        return [
            ['order' => 1, 'title' => 'ផ្នែកទី ១៖ ការណែនាំអំពីភាសា C', 'description' => 'ស្វែងយល់ពីប្រវត្តិ អត្ថប្រយោជន៍ ឧបករណ៍ និងរចនាសម្ព័ន្ធដំបូង។', 'lessons' => ['តើភាសា C ជាអ្វី?', 'ប្រវត្តិរបស់ភាសា C', 'អត្ថប្រយោជន៍នៃភាសា C', 'ការដំឡើងកម្មវិធីសរសេរកូដ', 'កម្មវិធីសួស្តីពិភពលោកដំបូង']],
            ['order' => 2, 'title' => 'ផ្នែកទី ២៖ អថេរ និងប្រភេទទិន្នន័យ', 'description' => 'រៀនអំពីការរក្សាទុកតម្លៃ ប្រភេទទិន្នន័យ និងការបម្លែងប្រភេទ។', 'lessons' => ['អថេរក្នុងភាសា C', 'តម្លៃថេរ', 'ចំនួនគត់', 'ចំនួនទសភាគ និងតួអក្សរ', 'ការបម្លែងប្រភេទទិន្នន័យ']],
            ['order' => 3, 'title' => 'ផ្នែកទី ៣៖ ការបញ្ចូល និងបង្ហាញទិន្នន័យ', 'description' => 'ប្រើ printf scanf និងបច្ចេកទេសបង្ហាញទិន្នន័យឲ្យត្រឹមត្រូវ។', 'lessons' => ['ការបង្ហាញទិន្នន័យដោយ printf', 'ការទទួលទិន្នន័យដោយ scanf', 'សញ្ញាកំណត់ទ្រង់ទ្រាយ', 'តួអក្សរគេច', 'ការប្រើ getchar និង putchar']],
            ['order' => 4, 'title' => 'ផ្នែកទី ៤៖ សញ្ញាប្រមាណវិធី', 'description' => 'រៀនសញ្ញាប្រមាណវិធីដែលប្រើក្នុងការគណនា ប្រៀបធៀប និងគ្រប់គ្រង logic។', 'lessons' => ['សញ្ញាគណិតវិទ្យា', 'សញ្ញាផ្តល់តម្លៃ', 'សញ្ញាប្រៀបធៀប', 'សញ្ញា logic', 'លំដាប់អាទិភាពសញ្ញាប្រមាណវិធី']],
            ['order' => 5, 'title' => 'ផ្នែកទី ៥៖ ការសម្រេចចិត្ត', 'description' => 'សរសេរកូដដែលអាចជ្រើសរើសផ្លូវដំណើរការផ្សេងៗតាមលក្ខខណ្ឌ។', 'lessons' => ['ការប្រើ if', 'ការប្រើ if else', 'ការប្រើ else if', 'ការប្រើ switch', 'សញ្ញាលក្ខខណ្ឌខ្លី']],
            ['order' => 6, 'title' => 'ផ្នែកទី ៦៖ រង្វិលជុំ', 'description' => 'គ្រប់គ្រងការធ្វើឡើងវិញដោយ while do while for និងការបញ្ឈប់ loop។', 'lessons' => ['រង្វិលជុំ while', 'រង្វិលជុំ do while', 'រង្វិលជុំ for', 'រង្វិលជុំជាន់គ្នា', 'ការប្រើ break និង continue']],
            ['order' => 7, 'title' => 'ផ្នែកទី ៧៖ មុខងារ', 'description' => 'បំបែកកម្មវិធីជាផ្នែកតូចៗដោយប្រើមុខងារ ប៉ារ៉ាម៉ែត្រ តម្លៃត្រឡប់ និងការហៅខ្លួនឯង។', 'lessons' => ['សេចក្ដីណែនាំអំពីមុខងារ', 'ការប្រកាស និងកំណត់មុខងារ', 'ការហៅមុខងារ', 'តម្លៃត្រឡប់ និងប៉ារ៉ាម៉ែត្រ', 'មុខងារហៅខ្លួនឯង']],
            ['order' => 8, 'title' => 'ផ្នែកទី ៨៖ អារេ', 'description' => 'រៀនរក្សាទុកទិន្នន័យច្រើនក្នុងឈ្មោះតែមួយ និងប្រើ loop ដើម្បីដំណើរការ។', 'lessons' => ['អារេមួយវិមាត្រ', 'អារេពីរវិមាត្រ', 'អារេច្រើនវិមាត្រ', 'ការរត់កាត់អារេ', 'ការផ្ញើអារេទៅមុខងារ']],
            ['order' => 9, 'title' => 'ផ្នែកទី ៩៖ ខ្សែអក្សរ', 'description' => 'គ្រប់គ្រងអត្ថបទក្នុង C ដោយប្រើ character array និងមុខងារ string។', 'lessons' => ['សេចក្ដីណែនាំអំពីខ្សែអក្សរ', 'ការបញ្ចូលខ្សែអក្សរ', 'ការបង្ហាញខ្សែអក្សរ', 'មុខងារ strlen strcpy និង strcat', 'ការប្រៀបធៀប និងកែប្រែខ្សែអក្សរ']],
            ['order' => 10, 'title' => 'ផ្នែកទី ១០៖ ចង្អុលអាសយដ្ឋាន', 'description' => 'យល់ពីអាសយដ្ឋានអង្គចងចាំ ការគណនាចង្អុល និងទំនាក់ទំនងជាមួយអារេនិងមុខងារ។', 'lessons' => ['សេចក្ដីណែនាំអំពីចង្អុលអាសយដ្ឋាន', 'អថេរចង្អុលអាសយដ្ឋាន', 'ការគណនាចង្អុលអាសយដ្ឋាន', 'ចង្អុលអាសយដ្ឋានទៅចង្អុលអាសយដ្ឋាន', 'ចង្អុលអាសយដ្ឋានជាមួយអារេ និងមុខងារ']],
            ['order' => 11, 'title' => 'ផ្នែកទី ១១៖ រចនាសម្ព័ន្ធទិន្នន័យ', 'description' => 'បង្កើតប្រភេទទិន្នន័យផ្ទាល់ខ្លួនសម្រាប់ទិន្នន័យដែលមានច្រើនវាល។', 'lessons' => ['សេចក្ដីណែនាំអំពីរចនាសម្ព័ន្ធទិន្នន័យ', 'រចនាសម្ព័ន្ធទិន្នន័យជាន់គ្នា', 'អារេនៃរចនាសម្ព័ន្ធទិន្នន័យ', 'ការផ្ញើរចនាសម្ព័ន្ធទៅមុខងារ', 'ការប្រើឈ្មោះកាត់ប្រភេទទិន្នន័យ']],
            ['order' => 12, 'title' => 'ផ្នែកទី ១២៖ សហភាព និងបញ្ជីតម្លៃថេរ', 'description' => 'ស្វែងយល់ពីសហភាព បញ្ជីតម្លៃថេរ និងពេលវេលាដែលគួរប្រើវា។', 'lessons' => ['សេចក្ដីណែនាំអំពីសហភាព', 'ភាពខុសគ្នារវាងសហភាព និងរចនាសម្ព័ន្ធ', 'សេចក្ដីណែនាំអំពីបញ្ជីតម្លៃថេរ', 'ការប្រើបញ្ជីតម្លៃថេរជាមួយការជ្រើសរើសករណី', 'ការអនុវត្តសហភាព និងបញ្ជីតម្លៃថេរ']],
            ['order' => 13, 'title' => 'ផ្នែកទី ១៣៖ អង្គចងចាំថាមវន្ត', 'description' => 'ប្រើ malloc calloc realloc និង free ដើម្បីគ្រប់គ្រងអង្គចងចាំដោយដៃ។', 'lessons' => ['មូលដ្ឋានអង្គចងចាំថាមវន្ត', 'ការប្រើ malloc', 'ការប្រើ calloc', 'ការប្រើ realloc', 'ការប្រើ free ដើម្បីការពារ memory leak']],
            ['order' => 14, 'title' => 'ផ្នែកទី ១៤៖ ការគ្រប់គ្រងឯកសារ', 'description' => 'អាន សរសេរ និងរក្សាទុកទិន្នន័យក្នុង file ដោយប្រើមុខងារ File I/O។', 'lessons' => ['ការបើកឯកសារដោយ fopen', 'ការបិទឯកសារដោយ fclose', 'ការសរសេរទៅឯកសារដោយ fprintf', 'ការអានពីឯកសារដោយ fscanf', 'ការប្រើ fread និង fwrite']],
            ['order' => 15, 'title' => 'ផ្នែកទី ១៥៖ ដំណាក់កាលមុនបកប្រែកូដ', 'description' => 'យល់ពីដំណាក់កាលមុនបកប្រែកូដ និងការប្រើបញ្ជាសំខាន់ៗ។', 'lessons' => ['ការនាំចូលបណ្ណាល័យ', 'ការកំណត់តម្លៃថេរជាមុន', 'ការបង្កើតម៉ាក្រូ', 'ការបង្កើតឯកសារក្បាល', 'ការបកប្រែតាមលក្ខខណ្ឌ']],
            ['order' => 16, 'title' => 'ផ្នែកទី ១៦៖ កំហុស និងការកែតម្រូវ', 'description' => 'ស្គាល់ប្រភេទកំហុស និងបច្ចេកទេសរកកំហុសសម្រាប់អ្នករៀន C។', 'lessons' => ['កំហុសទូទៅក្នុងភាសា C', 'កំហុសពេលបកប្រែកូដ', 'កំហុសពេលដំណើរការ', 'កំហុសតក្កវិជ្ជា', 'បច្ចេកទេសរកកំហុស']],
            ['order' => 17, 'title' => 'ផ្នែកទី ១៧៖ របៀបសរសេរកូដល្អ', 'description' => 'ពង្រឹងស្ទីលកូដ ឈ្មោះ variable formatting និង performance។', 'lessons' => ['ស្ទីលសរសេរកូដ', 'របៀបដាក់ឈ្មោះ', 'ការរៀបចំទ្រង់ទ្រាយកូដ', 'គន្លឹះបង្កើនល្បឿន', 'ការរៀបចំ project ឲ្យងាយថែទាំ']],
            ['order' => 18, 'title' => 'ផ្នែកទី ១៨៖ គម្រោងតូចៗ', 'description' => 'អនុវត្តចំណេះដឹងតាមរយៈគម្រោងតូចៗដែលស្រដៀងការងារពិត។', 'lessons' => ['គម្រោងម៉ាស៊ីនគិតលេខ', 'គម្រោងគ្រប់គ្រងនិស្សិត', 'គម្រោងគ្រប់គ្រងបណ្ណាល័យ', 'គម្រោងប្រព័ន្ធធនាគារ', 'គម្រោងគ្រប់គ្រងស្តុកទំនិញ']],
            ['order' => 19, 'title' => 'ផ្នែកទី ១៩៖ លំហាត់អនុវត្ត', 'description' => 'លំហាត់សម្រាប់ពង្រឹងមេរៀន តេស្តលទ្ធផល និងស្វែងរកកំហុស។', 'lessons' => ['សំណួរជ្រើសរើសចម្លើយ', 'បំពេញចន្លោះទំនេរ', 'បំពេញកូដឲ្យពេញលេញ', 'ទាយលទ្ធផលកូដ', 'ស្វែងរកកំហុសក្នុងកូដ']],
            ['order' => 20, 'title' => 'ផ្នែកទី ២០៖ ប្រឡងបញ្ចប់វគ្គ', 'description' => 'វាស់ស្ទង់ចំណេះដឹងចុងក្រោយតាមទ្រឹស្តី តេស្តសរសេរកូដ និងកិច្ចការអនុវត្ត។', 'lessons' => ['ការត្រៀមប្រឡងទ្រឹស្តី', 'ការធ្វើតេស្តសរសេរកូដ', 'កិច្ចការអនុវត្តជាក់ស្តែង', 'ការពិនិត្យគម្រោងចុងក្រោយ', 'ផែនការរៀនបន្តបន្ទាប់']],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function lessonPayload(
        Course $course,
        CourseSection $section,
        ?User $creator,
        int $sectionOrder,
        int $lessonOrder,
        string $title,
        string $sectionTitle
    ): array {
        $code = $this->sourceCode($sectionOrder);
        $difficulty = $sectionOrder <= 6 ? 'Beginner' : ($sectionOrder <= 13 ? 'Intermediate' : 'Advanced');
        $minutes = $difficulty === 'Beginner' ? 18 : ($difficulty === 'Intermediate' ? 25 : 35);

        return [
            'course_id' => $course->id,
            'section_id' => $section->id,
            'title' => $title,
            'slug' => sprintf('c-section-%02d-lesson-%02d', $sectionOrder, $lessonOrder),
            'short_description' => "មេរៀននេះនឹងណែនាំអំពី {$title} ដោយប្រើភាសាខ្មែរ និងឧទាហរណ៍កូដ C ដែលងាយយល់។",
            'lesson_content' => $this->lessonContent($title, $sectionTitle),
            'source_code' => $code['code'],
            'expected_output' => $code['output'],
            'explanation' => "ក្នុងមេរៀន {$title} អ្នកត្រូវយល់ថាកម្មវិធី C ដំណើរការពីមុខងារ main ហើយបន្ទាត់នីមួយៗមានតួនាទីច្បាស់លាស់។ សូមអានកូដយឺតៗ ពិនិត្យសញ្ញា semicolon និងសាកល្បងកែតម្លៃខ្លះៗដើម្បីឃើញលទ្ធផលខុសគ្នា។",
            'code_explanation' => $this->codeExplanation($title),
            'common_mistakes' => "កំហុសដែលជួបញឹកញាប់ក្នុង {$title} គឺភ្លេចសញ្ញា semicolon ប្រើឈ្មោះអថេរខុស ពុំបាន include header ត្រឹមត្រូវ និងមិនពិនិត្យប្រភេទទិន្នន័យឲ្យសមស្រប។",
            'tips' => "សូមសរសេរកូដជាប្លុកតូចៗ ដាក់ឈ្មោះអថេរឲ្យមានន័យ រៀបចំ indentation ឲ្យស្អាត និង compile ជាញឹកញាប់។ កុំចាំឲ្យកូដវែងពេកទើបសាកល្បង។",
            'summary' => "សរុបមក {$title} ជាជំនាញសំខាន់មួយក្នុងវគ្គ C Programming។ បើអ្នកយល់ឧទាហរណ៍នេះ អ្នកអាចបន្តទៅមេរៀនបន្ទាប់ដោយមានមូលដ្ឋានរឹងមាំ។",
            'exercise' => "លំហាត់៖ ១) កែតម្លៃក្នុងកូដ ហើយព្យាករណ៍លទ្ធផល។ ២) បន្ថែម printf មួយបន្ទាត់។ ៣) សរសេរកម្មវិធីថ្មីតូចមួយដែលប្រើគំនិត {$title}។ ៤) ស្វែងរកកំហុសមួយក្នុងកូដរបស់អ្នក ហើយកត់ត្រាវិធីកែ។",
            'quiz' => $this->quiz($title),
            'difficulty_level' => $difficulty,
            'estimated_learning_time' => $minutes,
            'video_url' => 'https://www.youtube.com/watch?v=KJgsSFOSQv0',
            'order_number' => $lessonOrder,
            'status' => 'published',
            'created_by' => $creator?->id,
        ];
    }

    private function lessonContent(string $title, string $sectionTitle): string
    {
        return <<<HTML
<h2>{$title}</h2>
<p>មេរៀននេះស្ថិតក្នុង {$sectionTitle}។ គោលបំណងគឺឲ្យអ្នករៀនយល់ពីគំនិតសំខាន់ៗដោយចាប់ពីនិយមន័យ បន្ទាប់មកមើលឧទាហរណ៍ និងចុងក្រោយអនុវត្តដោយខ្លួនឯង។</p>
<p>នៅពេលរៀនភាសា C អ្នកគួរចងចាំថា កូដត្រូវបាន compile មុនពេលដំណើរការ។ ដូច្នេះ syntax តូចៗដូចជា semicolon សញ្ញា quote វង់ក្រចក និងប្រភេទទិន្នន័យ មានសារៈសំខាន់ខ្លាំងណាស់។</p>
<p>សូមអានកូដខាងក្រោម បន្ទាប់មកព្យាយាមប្ដូរតម្លៃ ឬបន្ថែមបន្ទាត់ថ្មី ដើម្បីឲ្យការយល់ដឹងកើតឡើងពីការអនុវត្តជាក់ស្តែង។</p>
HTML;
    }

    private function codeExplanation(string $title): string
    {
        return "ការពន្យល់កូដសម្រាប់ {$title}៖ បន្ទាត់ #include <stdio.h> នាំចូលមុខងារ input/output។ មុខងារ main() ជាចំណុចចាប់ផ្តើមកម្មវិធី។ printf() បង្ហាញលទ្ធផលលើអេក្រង់។ return 0 បញ្ជាក់ថាកម្មវិធីបញ្ចប់ដោយជោគជ័យ។";
    }

    /**
     * @return array{code:string,output:string}
     */
    private function sourceCode(int $sectionOrder): array
    {
        return match ($sectionOrder) {
            1 => ['code' => <<<'CODE'
#include <stdio.h>

int main() {
    printf("Hello, Sala Code!\n");
    return 0;
}
CODE, 'output' => 'Hello, Sala Code!'],
            2 => ['code' => <<<'CODE'
#include <stdio.h>

int main() {
    int age = 20;
    float score = 95.5;
    char grade = 'A';

    printf("Age: %d\n", age);
    printf("Score: %.1f\n", score);
    printf("Grade: %c\n", grade);
    return 0;
}
CODE, 'output' => "Age: 20\nScore: 95.5\nGrade: A"],
            3 => ['code' => <<<'CODE'
#include <stdio.h>

int main() {
    int number;

    printf("Enter a number: ");
    scanf("%d", &number);
    printf("You entered: %d\n", number);
    return 0;
}
CODE, 'output' => "Enter a number: 7\nYou entered: 7"],
            4 => ['code' => <<<'CODE'
#include <stdio.h>

int main() {
    int a = 10;
    int b = 3;

    printf("Sum: %d\n", a + b);
    printf("Remainder: %d\n", a % b);
    printf("Is greater: %d\n", a > b);
    return 0;
}
CODE, 'output' => "Sum: 13\nRemainder: 1\nIs greater: 1"],
            5 => ['code' => <<<'CODE'
#include <stdio.h>

int main() {
    int score = 75;

    if (score >= 90) {
        printf("Excellent\n");
    } else if (score >= 50) {
        printf("Pass\n");
    } else {
        printf("Fail\n");
    }
    return 0;
}
CODE, 'output' => 'Pass'],
            6 => ['code' => <<<'CODE'
#include <stdio.h>

int main() {
    for (int i = 1; i <= 5; i++) {
        if (i == 3) {
            continue;
        }
        printf("%d\n", i);
    }
    return 0;
}
CODE, 'output' => "1\n2\n4\n5"],
            7 => ['code' => <<<'CODE'
#include <stdio.h>

int add(int a, int b) {
    return a + b;
}

int main() {
    printf("Result: %d\n", add(5, 7));
    return 0;
}
CODE, 'output' => 'Result: 12'],
            8 => ['code' => <<<'CODE'
#include <stdio.h>

int main() {
    int scores[3] = {80, 85, 90};

    for (int i = 0; i < 3; i++) {
        printf("%d\n", scores[i]);
    }
    return 0;
}
CODE, 'output' => "80\n85\n90"],
            9 => ['code' => <<<'CODE'
#include <stdio.h>
#include <string.h>

int main() {
    char name[20] = "Sala Code";

    printf("Name: %s\n", name);
    printf("Length: %lu\n", strlen(name));
    return 0;
}
CODE, 'output' => "Name: Sala Code\nLength: 9"],
            10 => ['code' => <<<'CODE'
#include <stdio.h>

int main() {
    int number = 25;
    int *ptr = &number;

    printf("Value: %d\n", *ptr);
    printf("Address exists: %d\n", ptr != NULL);
    return 0;
}
CODE, 'output' => "Value: 25\nAddress exists: 1"],
            11 => ['code' => <<<'CODE'
#include <stdio.h>

struct Student {
    int id;
    char name[30];
};

int main() {
    struct Student student = {1, "Dara"};
    printf("%d %s\n", student.id, student.name);
    return 0;
}
CODE, 'output' => '1 Dara'],
            12 => ['code' => <<<'CODE'
#include <stdio.h>

enum Level {
    BEGINNER,
    INTERMEDIATE,
    ADVANCED
};

int main() {
    enum Level level = INTERMEDIATE;
    printf("Level: %d\n", level);
    return 0;
}
CODE, 'output' => 'Level: 1'],
            13 => ['code' => <<<'CODE'
#include <stdio.h>
#include <stdlib.h>

int main() {
    int *numbers = malloc(3 * sizeof(int));
    numbers[0] = 10;
    numbers[1] = 20;
    numbers[2] = 30;

    printf("%d\n", numbers[1]);
    free(numbers);
    return 0;
}
CODE, 'output' => '20'],
            14 => ['code' => <<<'CODE'
#include <stdio.h>

int main() {
    FILE *file = fopen("lesson.txt", "w");

    if (file != NULL) {
        fprintf(file, "Sala Code\n");
        fclose(file);
        printf("File saved\n");
    }
    return 0;
}
CODE, 'output' => 'File saved'],
            15 => ['code' => <<<'CODE'
#include <stdio.h>
#define PI 3.14

int main() {
    float radius = 2;
    printf("Area: %.2f\n", PI * radius * radius);
    return 0;
}
CODE, 'output' => 'Area: 12.56'],
            16 => ['code' => <<<'CODE'
#include <stdio.h>

int main() {
    int total = 0;

    for (int i = 1; i <= 3; i++) {
        total += i;
        printf("Debug total: %d\n", total);
    }
    return 0;
}
CODE, 'output' => "Debug total: 1\nDebug total: 3\nDebug total: 6"],
            17 => ['code' => <<<'CODE'
#include <stdio.h>

int calculateTotal(int price, int quantity) {
    return price * quantity;
}

int main() {
    int total = calculateTotal(5, 4);
    printf("Total: %d\n", total);
    return 0;
}
CODE, 'output' => 'Total: 20'],
            18 => ['code' => <<<'CODE'
#include <stdio.h>

int main() {
    int choice = 1;

    switch (choice) {
        case 1:
            printf("Mini project menu\n");
            break;
        default:
            printf("Invalid choice\n");
    }
    return 0;
}
CODE, 'output' => 'Mini project menu'],
            19 => ['code' => <<<'CODE'
#include <stdio.h>

int main() {
    int answer = 6 * 7;
    printf("Answer: %d\n", answer);
    return 0;
}
CODE, 'output' => 'Answer: 42'],
            default => ['code' => <<<'CODE'
#include <stdio.h>

int main() {
    printf("Final exam practice\n");
    return 0;
}
CODE, 'output' => 'Final exam practice'],
        };
    }

    /**
     * @return array<int, array{question:string,options:array<int,string>,answer:string}>
     */
    private function quiz(string $title): array
    {
        return [
            [
                'question' => "តើគោលបំណងសំខាន់នៃមេរៀន {$title} គឺអ្វី?",
                'options' => ['យល់គំនិត និងអនុវត្តកូដ', 'លុបកម្មវិធីចេញ', 'ប្តូរ operating system', 'រៀនរចនារូបភាព'],
                'answer' => 'យល់គំនិត និងអនុវត្តកូដ',
            ],
            [
                'question' => 'តើមុខងារ main() មានតួនាទីអ្វីក្នុងកម្មវិធី C?',
                'options' => ['ជាចំណុចចាប់ផ្តើមកម្មវិធី', 'ជាឯកសាររូបភាព', 'ជាឈ្មោះ compiler', 'ជាអថេរថេរ'],
                'answer' => 'ជាចំណុចចាប់ផ្តើមកម្មវិធី',
            ],
            [
                'question' => 'តើសញ្ញា semicolon ត្រូវប្រើនៅទីណា?',
                'options' => ['ចុង statement ភាគច្រើន', 'ចុងឈ្មោះ file ប៉ុណ្ណោះ', 'មុខ #include', 'ក្នុង comment តែប៉ុណ្ណោះ'],
                'answer' => 'ចុង statement ភាគច្រើន',
            ],
            [
                'question' => 'តើវិធីរៀនល្អសម្រាប់មេរៀននេះគឺអ្វី?',
                'options' => ['អាន ពិនិត្យកូដ សាកល្បង និងកែតម្លៃ', 'ចម្លងដោយមិន compile', 'រំលងលំហាត់ទាំងអស់', 'មើលតែ output'],
                'answer' => 'អាន ពិនិត្យកូដ សាកល្បង និងកែតម្លៃ',
            ],
        ];
    }
}
