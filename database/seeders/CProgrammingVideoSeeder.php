<?php

namespace Database\Seeders;

use App\Models\ProgrammingLanguage;
use App\Models\Video;
use App\Models\VideoPlaylist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CProgrammingVideoSeeder extends Seeder
{
    public function run(): void
    {
        $language = ProgrammingLanguage::updateOrCreate(
            ['slug' => 'c'],
            [
                'name' => 'C Programming',
                'description' => 'Learn C programming from fundamentals through core data structures and practical problem solving.',
                'difficulty' => 'Beginner',
                'estimated_time' => 240,
                'status' => 'published',
                'order_number' => 1,
            ]
        );
        $playlist = VideoPlaylist::updateOrCreate(['slug' => 'c'], [
            'name' => 'C Programming',
            'description' => 'Learn C programming from fundamentals through core data structures and practical problem solving.',
            'status' => 'published', 'order_number' => 1,
        ]);

        $lessons = [
            ['Features and the First C Program', 'rLf3jnHxSmU', 'Explore C features, header files, functions, and the structure of a first program.'],
            ['Introduction to C Programming', '4OGMB4Fhh50', 'Why C matters, how programs work, and what this course covers.'],
            ['Variables and Data Types', 'fO4FwJOShdc', 'Learn how to declare, initialize, assign, and use variables in C.'],
            ['Basic Output with printf', 'VXol2-SoUy8', 'Format and display program output using the C standard library printf function.'],
            ['Basic Input with scanf', 'ZSZwDARaQYI', 'Read typed input safely with scanf, format specifiers, and address operators.'],
            ['Operators in C', '50Pb27JoUrw', 'Understand the major operator categories used to build C expressions.'],
            ['Decision Making with if-else', 'Led5aHdLoT4', 'Control program flow with if, else-if, nested conditions, and else.'],
            ['The switch Statement', '-JMSaLRqsgo', 'Use switch and case labels for clear multi-way decisions.'],
            ['for and while Loops', 'qUPXsPtWGoY', 'Repeat work with entry-controlled for and while loops.'],
            ['The do-while Loop', 'TjkJQly2YCw', 'Use an exit-controlled loop when the body must run at least once.'],
            ['Functions in C', '3lqgdqoY83o', 'Organize reusable logic with function declarations, definitions, and calls.'],
            ['Arrays in C', '55l-aZ7_F24', 'Understand arrays as fixed-size collections of elements of the same type.'],
            ['Strings in C', 'l7zI3nswO1g', 'Work with character arrays, string input, and common string operations.'],
            ['Pointers in C', 'f2i0CnUOniA', 'Learn how pointers store addresses and refer to values in memory.'],
            ['Structures in C', 'zmRxC7gYw-g', 'Group related values of different types using C structures.'],
            ['File Handling in C', 'Wslbb1UQUSc', 'Introduction to opening, reading, writing, closing, and handling files.'],
            ['Practice: Fibonacci Series', 'D59HhCkcmNA', 'Apply variables, loops, and expressions by generating the Fibonacci series.'],
            ['C Programming Course Review and Practice', 'KJgsSFOSQv0', 'Review core C concepts and reinforce them through a complete beginner course.'],
        ];

        $videoIds = collect($lessons)->pluck(1);

        // Remove only legacy generic C placeholders. Admin-created C lessons are preserved.
        Video::where('playlist_id', $playlist->id)
            ->whereNotIn('youtube_video_id', $videoIds)
            ->where(function ($query): void {
                $query->whereNull('youtube_video_id')->orWhereIn('title', ['C', 'C Programming']);
            })
            ->delete();

        foreach ($lessons as $index => [$title, $videoId, $description]) {
            Video::updateOrCreate(
                ['playlist_id' => $playlist->id, 'youtube_video_id' => $videoId],
                [
                    'programming_language_id' => $language->id,
                    'playlist_title' => $language->name,
                    'playlist_slug' => $language->slug,
                    'title' => $title,
                    'slug' => Str::slug($title),
                    'description' => $description,
                    'youtube_link' => 'https://www.youtube.com/watch?v=' . $videoId,
                    'thumbnail' => null,
                    'duration' => null,
                    'order_number' => $index + 1,
                    'status' => 'published',
                ]
            );
        }
    }
}
