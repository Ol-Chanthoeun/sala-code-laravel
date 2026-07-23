<?php

namespace Database\Seeders;

use App\Models\ProgrammingLanguage;
use App\Models\Quiz;
use App\Models\QuizCategory;
use App\Models\QuizChoice;
use App\Models\QuizQuestion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LmsQuizSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->languagePlans() as $order => $plan) {
            $language = ProgrammingLanguage::updateOrCreate(
                ['slug' => $plan['slug']],
                [
                    'name' => $plan['name'],
                    'logo' => $plan['logo'],
                    'description' => $plan['description'],
                    'difficulty' => $plan['difficulty'],
                    'estimated_time' => $plan['estimated_time'],
                    'status' => 'published',
                    'order_number' => $order + 1,
                ]
            );

            $language->categories()->delete();

            foreach ($plan['categories'] as $categoryOrder => $categoryTitle) {
                $category = QuizCategory::create([
                    'programming_language_id' => $language->id,
                    'title' => $categoryTitle,
                    'slug' => Str::slug($categoryTitle) ?: 'category-' . ($categoryOrder + 1),
                    'description' => 'Practice ' . $categoryTitle . ' with focused LMS-style questions.',
                    'difficulty' => $this->difficultyForOrder($categoryOrder + 1),
                    'status' => 'published',
                    'order_number' => $categoryOrder + 1,
                ]);

                $quiz = Quiz::create([
                    'programming_language_id' => $language->id,
                    'quiz_category_id' => $category->id,
                    'title' => $categoryTitle . ' Quiz',
                    'slug' => Str::slug($categoryTitle . ' Quiz') ?: 'quiz-' . ($categoryOrder + 1),
                    'description' => 'Assess your understanding of ' . $categoryTitle . '.',
                    'difficulty' => $this->difficultyForOrder($categoryOrder + 1),
                    'estimated_time' => 10 + (($categoryOrder + 1) * 2),
                    'passing_score' => 60,
                    'status' => 'published',
                    'order_number' => $categoryOrder + 1,
                ]);

                for ($questionOrder = 1; $questionOrder <= 5; $questionOrder++) {
                    $this->createQuestion($quiz, $plan['name'], $categoryTitle, $questionOrder);
                }
            }
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function languagePlans(): array
    {
        return [
            [
                'name' => 'C Programming',
                'slug' => 'c-programming',
                'logo' => 'c-programming.png',
                'difficulty' => 'Beginner',
                'estimated_time' => 240,
                'description' => 'C quizzes from syntax basics to files and final assessment.',
                'categories' => ['Introduction to C', 'Hello World', 'Variables', 'Data Types', 'Operators', 'If Statement', 'Switch', 'Loops', 'Functions', 'Arrays', 'Strings', 'Pointers', 'Structures', 'File Handling', 'Final Test'],
            ],
            [
                'name' => 'C++',
                'slug' => 'cpp',
                'logo' => 'cpp.png',
                'difficulty' => 'Intermediate',
                'estimated_time' => 220,
                'description' => 'C++ quizzes covering syntax, OOP, STL, and projects.',
                'categories' => ['Introduction', 'Variables', 'Control Flow', 'Functions', 'Classes', 'Objects', 'Inheritance', 'Polymorphism', 'STL', 'File Handling', 'Final Test'],
            ],
            [
                'name' => 'Python',
                'slug' => 'python',
                'logo' => 'PythonCourse.png',
                'difficulty' => 'Beginner',
                'estimated_time' => 260,
                'description' => 'Python quizzes for basics, data structures, modules, and OOP.',
                'categories' => ['Basics', 'Variables', 'Conditionals', 'Loops', 'Functions', 'List', 'Dictionary', 'OOP', 'Modules', 'File', 'Final Test'],
            ],
            [
                'name' => 'Java',
                'slug' => 'java',
                'logo' => 'JavaCourse.jpg',
                'difficulty' => 'Intermediate',
                'estimated_time' => 260,
                'description' => 'Java quizzes for syntax, classes, OOP, collections, and exceptions.',
                'categories' => ['Basics', 'Variables', 'Control Flow', 'Methods', 'Classes', 'Objects', 'Inheritance', 'Interfaces', 'Collections', 'Exceptions', 'Final Test'],
            ],
            [
                'name' => 'PHP',
                'slug' => 'php',
                'logo' => 'SalaCode-Logo.png',
                'difficulty' => 'Beginner',
                'estimated_time' => 220,
                'description' => 'PHP quizzes for backend foundations and web development.',
                'categories' => ['Basics', 'Variables', 'Arrays', 'Forms', 'Functions', 'Sessions', 'Files', 'Database', 'Security', 'Final Test'],
            ],
            [
                'name' => 'Laravel',
                'slug' => 'laravel',
                'logo' => 'LogoSalaCode.png',
                'difficulty' => 'Advanced',
                'estimated_time' => 300,
                'description' => 'Laravel quizzes covering MVC, routing, controllers, Eloquent, auth, and deployment.',
                'categories' => ['Introduction', 'Routing', 'Controllers', 'Blade', 'Validation', 'Eloquent', 'Migrations', 'Authentication', 'Policies', 'Deployment', 'Final Test'],
            ],
            [
                'name' => 'HTML',
                'slug' => 'html',
                'logo' => 'HTML-Course.jpg',
                'difficulty' => 'Beginner',
                'estimated_time' => 150,
                'description' => 'HTML quizzes from document structure to semantic markup.',
                'categories' => ['Introduction', 'Elements', 'Attributes', 'Links', 'Images', 'Forms', 'Tables', 'Semantic HTML', 'Accessibility', 'Final Test'],
            ],
            [
                'name' => 'CSS',
                'slug' => 'css',
                'logo' => 'CSS-Course.png',
                'difficulty' => 'Beginner',
                'estimated_time' => 180,
                'description' => 'CSS quizzes for selectors, layout, responsive design, and animation.',
                'categories' => ['Introduction', 'Selectors', 'Box Model', 'Colors', 'Typography', 'Flexbox', 'Grid', 'Responsive Design', 'Animation', 'Final Test'],
            ],
            [
                'name' => 'JavaScript',
                'slug' => 'javascript',
                'logo' => 'JavaScript-Course.png',
                'difficulty' => 'Intermediate',
                'estimated_time' => 260,
                'description' => 'JavaScript quizzes for frontend logic, DOM, async, and modules.',
                'categories' => ['Basics', 'Variables', 'Functions', 'Arrays', 'Objects', 'DOM', 'Events', 'Async', 'Modules', 'Final Test'],
            ],
        ];
    }

    private function createQuestion(Quiz $quiz, string $language, string $category, int $order): void
    {
        $question = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => "សំណួរទី {$order}៖ តើគោលគំនិតសំខាន់មួយក្នុង {$language} ផ្នែក {$category} គឺអ្វី?",
            'explanation' => "The correct answer focuses on understanding and applying {$category} concepts in {$language}.",
            'difficulty' => $quiz->difficulty,
            'points' => 1,
            'order_number' => $order,
        ]);

        $choices = [
            "យល់ និងអនុវត្ត {$category}",
            'មិនអើពើ syntax និងរំលងការអនុវត្ត',
            'ទន្ទេញ output ដោយមិនអាន code',
            'លុបឯកសាររបស់ project',
        ];

        foreach ($choices as $index => $choiceText) {
            $choice = QuizChoice::create([
                'quiz_question_id' => $question->id,
                'choice_text' => $choiceText,
                'is_correct' => $index === 0,
                'order_number' => $index + 1,
            ]);

            if ($index === 0) {
                $question->update(['correct_choice_id' => $choice->id]);
            }
        }
    }

    private function difficultyForOrder(int $order): string
    {
        return $order <= 4 ? 'Easy' : ($order <= 8 ? 'Medium' : 'Hard');
    }
}
