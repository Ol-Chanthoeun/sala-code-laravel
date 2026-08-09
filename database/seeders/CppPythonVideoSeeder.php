<?php

namespace Database\Seeders;

use App\Models\ProgrammingLanguage;
use App\Models\Video;
use App\Models\VideoPlaylist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CppPythonVideoSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedCourse('cpp', 'C++', 2, [
            ['Introduction to C++ Programming', 'M5UAO6n1LmQ'],
            ['Installing a C++ IDE', 'JSDN5zNA37k'],
            ['Your First C++ Program', 'Udn7DRCfuPI'],
            ['Keywords and Identifiers', 'G_hWFHBH2Y0'],
            ['Declaring and Defining Variables', 'RrVlY_csalc'],
            ['Initializing Variables', '2iAayF06PTY'],
            ['Constants in C++', 'WikvQ6TFXQ8'],
            ['Fundamental Data Types', '1XUgS24zogM'],
            ['Basic Input and Output', 'yGL0CZlpzwM'],
            ['Type Conversion', 'FZh2T5mhu7I'],
            ['Arithmetic Operators', '1XbtijPZKUs'],
            ['Conditional Statements and Loops', 'qR9U6bKxJ7g'],
            ['Functions in C++', 'P08Z_NC8GuY'],
            ['Arrays in C++', '8wmn7k1TTcI'],
            ['Vectors in C++', 'NWg38xWYzEg'],
            ['Pointers in C++', 'qYEjR6M0wSk'],
            ['if-else Statement in C++', '9-BjXs1vMSc'],
            ['C++ Full Course and Practice', '-TkoO8Z07hI'],
        ]);

        $this->seedCourse('python', 'Python', 3, [
            ['Introduction to Python', '7wnove7K-ZQ'],
            ['Modules and pip', 'xwKO_y2gHxQ'],
            ['Your First Python Program', '7IWOYhfAcVg'],
            ['Variables and Data Types', 'ORCuz7s5cCY'],
            ['Taking User Input', 'WvG-R-xXouA'],
            ['Strings in Python', 'kMNFQYArrLg'],
            ['if, elif and else', 'ceiuLR2ysas'],
            ['for Loops', 'fIYVzKp0q5w'],
            ['while Loops', '-tCFyIyKVx0'],
            ['Functions in Python', 'dyvxxJSGUsE'],
            ['Lists in Python', 'eF6nK5bSlmg'],
            ['Tuples in Python', 'PipsOUDKrVk'],
            ['Sets in Python', 'l3kCO8cVA6o'],
            ['Dictionaries in Python', 'j2G68uQtOwM'],
            ['Virtual Environments', 'nt6LlFTWOkg'],
            ['File Input and Output', 'eDBPlcWYses'],
            ['Introduction to OOP', 'HQnoYzxOHMw'],
            ['Classes and Objects', 'a7baAGCBA9U'],
            ['Library Management Mini Project', 'mlDZTSH2FFc'],
            ['Python Full Course and Practice', '_uQrJ0TkZlc'],
        ]);
    }

    private function seedCourse(string $slug, string $name, int $order, array $lessons): void
    {
        $playlist = VideoPlaylist::updateOrCreate(['slug' => $slug], [
            'name' => $name, 'description' => "A structured {$name} video course using real educational YouTube lessons.",
            'status' => 'published', 'order_number' => $order,
        ]);
        $language = ProgrammingLanguage::where('slug', $slug)->first();
        $ids = collect($lessons)->pluck(1);
        Video::where('playlist_id', $playlist->id)->whereNotIn('youtube_video_id', $ids)
            ->where(fn ($query) => $query->whereNull('youtube_video_id')
                ->orWhere('youtube_video_id', 'il51Y5soSmk')
                ->orWhereIn('title', [$name, $name . ' Programming']))->delete();

        foreach ($lessons as $index => [$title, $id]) {
            Video::updateOrCreate(['playlist_id' => $playlist->id, 'youtube_video_id' => $id], [
                'programming_language_id' => $language?->id,
                'playlist_title' => $name, 'playlist_slug' => $slug,
                'title' => $title, 'slug' => Str::slug($title),
                'description' => "Lesson " . ($index + 1) . " in the {$name} learning path.",
                'youtube_link' => 'https://www.youtube.com/watch?v=' . $id,
                'thumbnail' => null, 'duration' => null,
                'order_number' => $index + 1, 'status' => 'published',
            ]);
        }
    }
}
