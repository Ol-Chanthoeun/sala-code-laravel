@extends('layouts.frontend')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/c_programming.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/prismjs/themes/prism-tomorrow.min.css">
@endpush

@section('content')

 <!-- Sidebar Toggle Button (ADD ONLY) -->
    <button class="sidebar-toggle" id="sidebarToggle" aria-label="Open sidebar" aria-expanded="false">
    ☰ មេរៀន
    </button>

    <!-- ===== MAIN LAYOUT ===== -->
    <div class="container">

        <!-- ===== SIDEBAR ===== -->
        <aside class="sidebar">
            <div class="section">
                <h3><a href="#lesson1">មេរៀនទី 1: សេចក្តីណែនាំ C</a></h3>
                <ul>
                    <li><a href="#point1.1">1-1 C programming គឺអ្វី?</a></li>
                    <li><a href="#point1.2">1-2 ប្រវត្តិ C</a></li>
                    <li><a href="#point1.3">1-3 ការប្រើប្រាស់ C</a></li>
                    <li><a href="#point1.4">1-4 Compiler និង IDE</a></li>
                </ul>
            </div>

            <div class="section">
                <h3><a href="#lesson2">មេរៀនទី 2: រចនាសម្ព័ន្ធ C</a></h3>
                <ul>
                    <li><a href="#point2.1">2-1 Structure C Program</a></li>
                    <li><a href="#point2.2">2-2 #include</a></li>
                    <li><a href="#point2.3">2-3 main()</a></li>
                    <li><a href="#point2.4">2-4 printf()</a></li>
                </ul>
            </div>

            <div class="section">
                <h3><a href="#lesson3">មេរៀនទី 3: Variable & Data Type</a></h3>
                <ul>
                    <li><a href="#point3.1">3-1 Variable</a></li>
                    <li><a href="#point3.2">3-2 Data Types</a></li>
                    <li><a href="#point3.3">3-3 Constant</a></li>
                    <li><a href="#point3.4">3-4 Format Specifier</a></li>
                </ul>
            </div>

            <div class="section">
                <h3><a href="#lesson4">មេរៀនទី 4: Operator</a></h3>
                <ul>
                    <li><a href="#point4.1">4-1 Arithmetic Operators</a></li>
                    <li><a href="#point4.2">4-2 Relational Operators</a></li>
                    <li><a href="#point4.3">4-3 Logical Operators</a></li>
                    <li><a href="#point4.4">4-4 Bitwise Operators</a></li>
                </ul>
            </div>

            <div class="section">
                <h3><a href="#lesson5">មេរៀនទី 5: Control Flow</a></h3>
                <ul>
                    <li><a href="#point5.1">5-1 If-Else Statement</a></li>
                    <li><a href="#point5.2">5-2 Switch Statement</a></li>
                    <li><a href="#point5.3">5-3 Loop (for, while, do-while)</a></li>
                    <li><a href="#point5.4">5-4 Break & Continue</a></li>
                </ul>
            </div>

            <div class="section">
                <h3><a href="#lesson6">មេរៀនទី 6: Function</a></h3>
                <ul>
                    <li><a href="#point6.1">6-1 Function គឺអ្វី?</a></li>
                    <li><a href="#point6.2">6-2 ការបង្កើត និងការហៅ Function</a></li>
                    <li><a href="#point6.3">6-3 Parameter និង Return Value</a></li>
                    <li><a href="#point6.4">6-4 Recursion</a></li>
                </ul>
            </div>

            <div class="section">
                <h3><a href="#lesson7">មេរៀនទី 7: Array & String</a></h3>
                <ul>
                    <li><a href="#point7.1">7-1 Array គឺអ្វី?</a></li>
                    <li><a href="#point7.2">7-2 ការបង្កើត និងការប្រើប្រាស់ Array</a></li>
                    <li><a href="#point7.3">7-3 String គឺអ្វី?</a></li>
                    <li><a href="#point7.4">7-4 ការបង្កើត និងការប្រើប្រាស់ String</a></li>
                </ul>
            </div>

            <div class="section">
                <h3><a href="#lesson8">មេរៀនទី 8: Pointer</a></h3>
                <ul>
                    <li><a href="#point8.1">8-1 Pointer គឺអ្វី?</a></li>
                    <li><a href="#point8.2">8-2 ការបង្កើត និងការប្រើប្រាស់ Pointer</a></li>
                    <li><a href="#point8.3">8-3 Pointer និង Array</a></li>
                    <li><a href="#point8.4">8-4 Pointer និង Function</a></li>
                </ul>
            </div>

            <div class="section">
                <h3><a href="#lesson9">មេរៀនទី 9: Structure & Union</a></h3>
                <ul>
                    <li><a href="#point9.1">9-1 Structure គឺអ្វី?</a><a href=""></a></li>
                    <li><a href="#point9.2">9-2 ការបង្កើត និងការប្រើប្រាស់ Structure</a></li>
                    <li><a href="#point9.3">9-3 Union គឺអ្វី?</a></li>
                    <li><a href="#point9.4">9-4 ការបង្កើត និងការប្រើប្រាស់ Union</a></li>
                </ul>
            </div>

            <div class="section">
                <h3><a href="#lesson10">មេរៀនទី 10: File I/O</a></h3>
                <ul>
                    <li><a href="#point10.1">10-1 File I/O គឺអ្វី?</a></li>
                    <li><a href="#point10.2">10-2 ការបើក និងការបិទ File</a></li>
                    <li><a href="#point10.3">10-3 ការអាន និងការសរសេរ File</a></li>
                    <li><a href="#point10.4">10-4 Error Handling ក្នុង File I/O</a></li>
                </ul>
            </div>



        </aside>

        <!-- ===== CONTENT ===== -->
        <main class="content">
            <p>នៅក្នុងពិភពលោកនេះ តែងតែមានភាសានិយាយដើម្បីស្គាល់ ដឹង យល់ ពីគ្នាផ្សេងៗបាន ដូចជា៖</p>
            <ul>
                <li>ភាសាអង់គ្លេស	: Hello</li>
                <li>ភាសាបារាំង	: Bonjour</li>
                <li>ភាសាអេស្ប៉ាញ	: Hola</li>
                <li>ភាសាខ្មែរ		: សួស្តី</li>
            </ul>
            <p>ដោយហេតុនេះទើបណ្តាលឲ្យមនុស្សមានទំនាក់ទំនងជាមួយគ្នាបានតាមរយៈភាសាដែលពួកគេបានប្រើ ។</p>

            <p>ចុះ Computer វាមានភាសាដើម្បីមានទំនាក់ទំនងជាមួយគ្នាបានដែរឬទេ? បើបាន វាប្រើភាសាអី?
            Computer ក៏មានភាសាដើម្បីទំនាក់ទំនង ឬដើម្បីធ្វើអ្វីមួយបានដែរ ដូច្នេះ វាមានភាសាជាច្រើនដូចជា៖
            </p>

            <ul>
                <li>ភាសា C</li>
                <li>ភាសា C++</li>
                <li>ភាសា Java</li>
                <li>ភាសា Python</li>
            </ul>

            <p>និងមានភាសាជាច្រើនទៀត ដែលវាអាចប្រើដើម្បីដំណើរការអ្វីមួយបាន ។
            ក្នុងមេរៀននេះ យើងនឹងរៀនអំពីមូលដ្ឋានគ្រឹះនៃភាសា C ដែលគេប្រើប្រាស់ ។
            </p>

            <!-- Lesson1 -->
            <h2 id="lesson1" style="color: rgb(231, 90, 2);">មេរៀនទី 1: សេចក្តីណែនាំ C</h2>
            <h2 id="point1.1">1.1 C Programming គឺអ្វី?</h2>
            <p>
                C គឺជាភាសា Programming ដ៏មានឥទ្ធិពលនិងត្រូវបានប្រើប្រាស់យ៉ាងទូលំទូលាយ ។
            </p>

            

            <h2 id="point1.2">1.2 ប្រវត្តិ C</h2>
            <div class="history-dennis">
                
                <img src="images/c_programming/Dennis Ritchie (C).jpg" class="content-img">

                <p>C ត្រូវបានបង្កើតឡើងដោយលោក Dennis Ritchie នៅឆ្នាំ 1970 នៅ Bell Labs ហើយវាបាន
                ក្លាយជាឫសគល់សម្រាប់ភាសាកម្មវិធីជាច្រើនទៀតដូចជា  C++, Java និង Python ។

                C គឺដូចជាកូនចៅចម្បងរបស់ភាសា B និង BCPL។ វាត្រូវបានបង្កើតឡើងដើម្បីជួយសរសេរប្រព័ន្ធប្រតិបត្តិការ Unix។ ចាប់តាំងពីពេលនោះមក C បានរីកសាយភាយដូចជាដើមឈើធំមួយដែលមានមែកធាងរីកសាខាពាសពេញពិភពកម្មវិធីកុំព្យូទ័រ។
                </p>
            </div>

            <div class="history-box">
                <h3>History of C</h3>
                <ul>
                    <li>1972: Dennis Ritchie បង្កើត C នៅ Bell Labs</li>
                    <li>1978: សៀវភៅ "The C Programming Language"</li>
                    <li>1989: ANSI C</li>
                </ul>
            </div>

            <p>ហេតុអ្វីបានជាយើងត្រូវរៀន ភាសា C នេះ?</p>
            <ul>
                    <li>	វាជាភាសាកម្មវិធីមួយដែលត្រូវបានប្រើប្រាស់យ៉ាងទូលំទូលាយជាងគេមួយ។</li>
                    <li>បើអ្នកស្គាល់ C អ្នកនឹងងាយស្រួលរៀនភាសាកម្មវិធីពេញនិយមផ្សេងៗទៀត ដូចជា Java, Python, C++, C# ជាដើម ព្រោះ Syntax (របៀបសរសេរ) ស្រដៀងគ្នា។</li>
                    <li>វាជួយអោយអ្នកយល់អំពី Memory (អង្គចងចាំ), Performance (ល្បឿន/ប្រសិទ្ធភាព), និងរបៀបដែលកុំព្យូទ័រដំណើរការទិន្នន័យ។</li>
                    <li>C មានភាពបត់បែនខ្លាំង អាចប្រើបានទាំងផ្នែក Application និងផ្នែក Technology ផ្សេងៗ។</li>
            </ul>

            <h2 id="point1.3">1.3 ការប្រើប្រាស់ C</h2>
            <p>
                C ត្រូវបានប្រើសម្រាប់ Operating System, Game, Driver, Microcontroller និង Software ផ្សេងៗ។
            </p>
            <img src="images/operating-system.png" alt="operating-system" class="content-img">

            <h2 id="point1.4">1.4 Compiler និង IDE</h2>
            <p>
                ដើម្បីសរសេរ និងដំណើរការ C អ្នកត្រូវការឧបករណ៍ដែលហៅថា Compiler (កម្មវិធីបំលែង) និង IDE (Integrated Development Environment) ។
                Compiler គឺជាកម្មវិធីដែលបំលែងកូដ C របស់អ្នកទៅជាភាសាម៉ាស៊ីនដែលកុំព្យូទ័រអាចយល់បាន។ IDE គឺជាកម្មវិធីដែលផ្តល់បរិស្ថានសម្រាប់សរសេរ កែប្រែ និងដំណើរការ កូដ C របស់អ្នក។ ឧទាហរណ៍នៃ IDE មាន Visual Studio Code, Code::Blocks, និង Dev-C++។
            </p>
            <img src="images/c_programming/IDE.png" alt="IDE" class="content-img">

            <!-- Lesson2 -->
            <h2 id="lesson2" style="color: rgb(231, 90, 2);">មេរៀនទី 2: រចនាសម្ព័ន្ធ C</h2>
            <h2 id="point2.1">2.1 Structure C Program</h2>
            <p>
                រចនាសម្ព័ន្ធមូលដ្ឋាននៃកម្មវិធី C មានរូបរាងដូចខាងក្រោម៖
            </p>
            <pre class="language-c"><code>
#include &lt;stdio.h&gt;
int main() {
    <br>
    // កូដរបស់អ្នកនៅទីនេះ
    <br>
    return 0;
}
            </code></pre>

            <p><span>- int main:</span> គឺជាការប្រកាសដែលបង្កើត Function មួយដែលជាចំណុចចាប់ផ្តើមនៃកម្មវិធី C។ ការប្រកាសនេះត្រូវបានបញ្ជាក់ថា Function នេះមាន return type គឺ int និងមាន parameter គឺ void (គ្មាន parameter)។</p>

            <p><span>- return 0:</span> គឺជាការបញ្ជាក់ថាកម្មវិធីបានបញ្ចប់ដោយជោគជ័យ។ វាមានតម្លៃ 0 ដើម្បីបញ្ជាក់ថា Function main() បានដំណើរការដោយជោគជ័យ។</p>

            <h2 id="point2.2">#include</h2>
            <p>
                #include គឺជាការបញ្ជាក់ថាអ្នកចង់បញ្ចូល Header File ឬ Library មួយចំនួនទៅក្នុងកម្មវិធីរបស់អ្នក។ ឧទាហរណ៍ #include &lt;stdio.h&gt; គឺបញ្ចូល Standard Input Output Library ដែលមាន Function printf() សម្រាប់បង្ហាញអត្ថបទទៅកាន់ Console។
            </p>

            <h2 id="point2.3">main()</h2>
            <p>
                main() គឺជាផ្នែកសំខាន់នៃកម្មវិធី C ដែលជាចំណុចចាប់ផ្តើមនៃកម្មវិធី។ កូដដែលអ្នកសរសេរនៅក្នុង main() នឹងត្រូវបានដំណើរការដោយកុំព្យូទ័រ។ កម្មវិធី C ត្រូវតែមាន main() មួយគត់ ដើម្បីអោយវាដំណើរការបាន។
            </p>

            <h2 id="point2.4">printf()</h2>
            <p>
                printf() គឺជាផ្នែកមួយនៃ stdio.h library ដែលប្រើសម្រាប់បង្ហាញអត្ថបទ ឬតម្លៃទៅកាន់ Console។ វាមាន Syntax ដូចខាងក្រោម៖
            </p>
            <pre class="language-c"><code>
printf("អត្ថបទ %d", តម្លៃ);
            </code></pre>
            <p>
                នៅក្នុង printf() អ្នកអាចប្រើ Format Specifier ដើម្បីបង្ហាញតម្លៃប្រភេទផ្សេងៗបាន។ ឧទាហរណ៍ %d សម្រាប់ Integer, %f សម្រាប់ Float, និង %s សម្រាប់ String។
            </p>

            <!-- Lesson3 -->
            <h2 id="lesson3" style="color: rgb(231, 90, 2);">មេរៀនទី 3: Variable & Data Type</h2>
            <h2 id="point3.1">3.1 Variable</h2>
            <p>
                Variable គឺជាឈ្មោះដែលតំណាងឲ្យតម្លៃមួយនៅក្នុងកម្មវិធី។ វាអាចផ្ទុកតម្លៃប្រភេទផ្សេងៗបាន ដូចជា Integer, Float, String, និង Boolean។ នៅក្នុង C អ្នកត្រូវប្រកាស Variable មុនពេលប្រើវា ដោយបញ្ជាក់ប្រភេទទិន្នន័យរបស់វា។ ឧទាហរណ៍៖  
            </p>
            <pre class="language-c"><code>
int age = 25;
float height = 5.8;
char name[] = "John";
            </code></pre>
            <h2 id="point3.2">3.2 Data Types</h2>
            <p>
                C មាន Data Types មួយចំនួនដែលត្រូវបានប្រើសម្រាប់បង្កើត Variable និងផ្ទុកតម្លៃ។ ឧទាហរណ៍៖
            </p>
            <ul>
                <li>int: សម្រាប់តម្លៃគត់ (Integer)</li>
                <li>float: សម្រាប់តម្លៃទសភាគ (Floating-point)</li>
                <li>char: សម្រាប់តម្លៃតួអក្សរ (Character)</li>
                <li>double: សម្រាប់តម្លៃទសភាគមានភាពច្បាស់ល្អ (Double-precision floating-point)</li>
                <li>void: សម្រាប់បង្ហាញថាមិនមានតម្លៃ (No value)</li>
            </ul>
            <h2 id="point3.3">3.3 Constant</h2>
            <p>
                Constant គឺជាតម្លៃដែលមិនអាចផ្លាស់ប្ដូរបាននៅក្នុងកម្មវិធី។ នៅក្នុង C អ្នកអាចប្រើ const keyword ដើម្បីបង្កើត Constant។ ឧទាហរណ៍៖
            </p>
            <pre class="language-c"><code>
const float PI = 3.14;
            </code></pre>
            <h2 id="point3.4">3.4 Format Specifier</h2>
            <p>
                Format Specifier គឺជាសញ្ញាដែលប្រើសម្រាប់បង្ហាញតម្លៃប្រភេទផ្សេងៗនៅក្នុង printf()។ ឧទាហរណ៍៖
            </p>
            <ul>
                <li>%d: សម្រាប់ Integer</li>
                <li>%f: សម្រាប់ Float</li>
                <li>%s: សម្រាប់ String</li>
                <li>%c: សម្រាប់ Character</li>
                <li>%lf: សម្រាប់ Double</li>
            </ul>

            <!-- Lesson4 -->
            <h2 id="lesson4" style="color: rgb(231, 90, 2);">មេរៀនទី 4: Operator</h2>
            <h2 id="point4.1">4.1 Arithmetic Operators</h2>
            <p>
                Arithmetic Operators គឺជាការប្រើប្រាស់សញ្ញា ដើម្បីធ្វើការគណនាថ្លៃ។ ឧទាហរណ៍៖
            </p>
            <ul>
                <li>+ (Addition)</li>
                <li>- (Subtraction)</li>
                <li>* (Multiplication)</li>
                <li>/ (Division)</li>
                <li>% (Modulo - ការបាននៃការចែកដោយប្រភាគ)</li>
            </ul>
            <h2 id="point4.2">4.2 Relational Operators</h2>
            <p>
                Relational Operators គឺជាការប្រើប្រាស់សញ្ញា ដើម្បីប្រៀបធៀបតម្លៃពីរឬច្រើន។ ឧទាហរណ៍៖
            </p>
            <ul>
                <li>== (Equal to)</li>
                <li>!= (Not equal to)</li>
                <li>&gt; (Greater than)</li>
                <li>&lt; (Less than)</li>
                <li>&gt;= (Greater than or equal to)</li>
                <li>&lt;= (Less than or equal to)</li>
            </ul>
            <h2 id="point4.3">4.3 Logical Operators</h2>
            <p>
                Logical Operators គឺជាការប្រើប្រាស់សញ្ញា ដើម្បីបង្កើតលក្ខខណ្ឌដែលមានច្រើន។ ឧទាហរណ៍៖
            </p>
            <ul>
                <li>&amp;&amp; (Logical AND)</li>
                <li>|| (Logical OR)</li>
                <li>! (Logical NOT)</li>
            </ul>
            <h2 id="point4.4">4.4 Bitwise Operators</h2>
            <p>
                Bitwise Operators គឺជាការប្រើប្រាស់សញ្ញា ដើម្បីធ្វើការគណនាថ្លៃនៅលើកម្រិតប៊ីត (bit level)។ ឧទាហរណ៍៖
            </p>
            <ul>
                <li>&amp; (Bitwise AND)</li>
                <li>| (Bitwise OR)</li>
                <li>^ (Bitwise XOR)</li>
                <li>~ (Bitwise NOT)</li>
                <li>&lt;&lt; (Left shift)</li>
                <li>&gt;&gt; (Right shift)</li>
            </ul>

            <!-- Lesson5 -->
            <h2 id="lesson5" style="color: rgb(231, 90, 2);">មេរៀនទី 5: Control Flow</h2>
            <h2 id="point5.1">5.1 If-Else Statement</h2>
            <p>
                If-Else Statement គឺជាការប្រើប្រាស់សម្រាប់បង្កើតលក្ខខណ្ឌក្នុងកម្មវិធី។ វាអនុញ្ញាតឲ្យអ្នកដំណើរការកូដមួយចំនួន បើលក្ខខណ្ឌត្រូវបានបំពេញ។ ឧទាហរណ៍៖
            </p>
            <pre class="language-c"><code>
if (លក្ខខណ្ឌ) {
    // កូដដែលត្រូវបានដំណើរការបើលក្ខខណ្ឌត្រូវបានបំពេញ
} else {
    // កូដដែលត្រូវបានដំណើរការបើលក្ខខណ្ឌមិនត្រូវបានបំពេញ
}
            </code></pre>
            <h2 id="point5.2">5.2 Switch Statement</h2>
            <p>
                Switch Statement គឺជាការប្រើប្រាស់សម្រាប់បង្កើតលក្ខខណ្ឌដែលមានច្រើន។ វាអនុញ្ញាតឲ្យអ្នកដំណើរការកូដមួយចំនួន បើតម្លៃមួយស្មើនឹងតម្លៃណាមួយក្នុងករណី។ ឧទាហរណ៍៖
            </p>
            <pre class="language-c"><code>
switch (តម្លៃ) {
    case តម្លៃ1:
        // កូដដែលត្រូវបានដំណើរការបើតម្លៃស្មើនឹងតម្លៃ1
        break;
    case តម្លៃ2:
        // កូដដែលត្រូវបានដំណើរការបើតម្លៃ
        break;
    default:
        // កូដដែលត្រូវបានដំណើរការបើតម្លៃមិនស្មើនឹងតម្លៃណាមួយ
}
            </code></pre>
            <h2 id="point5.3">5.3 Loop (for, while, do-while)</h2>
            <p>
                Loop គឺជាការប្រើប្រាស់សម្រាប់ធ្វើការដំណើរការកូដមួយចំនួនជាបន្តបន្ទាប់។ C មាន Loop ចំនួនបី៖ for, while, និង do-while។ ឧទាហរណ៍៖
            </p>
            <pre class="language-c"><code>
// For Loop
for (initialization; condition; increment) {
    // កូដដែលត្រូវបានដំណើរការជាបន្តបន្ទាប់
}

// While Loop
while (condition) {
    // កូដដែលត្រូវបានដំណើរការជាបន្តបន្ទាប់
}

// Do-While Loop
do {
    // កូដដែលត្រូវបានដំណើរការជាបន្តបន្ទាប់
} while (condition);
            </code></pre>
            <h2 id="point5.4">5.4 Break &amp; Continue</h2>
            <p>
                Break និង Continue គឺជាការប្រើប្រាស់សម្រាប់គ្រប់គ្រងលំហូរនៃ Loop។ Break ត្រូវបានប្រើសម្រាប់បញ្ឈប់ Loop ទាំងមូល ហើយ Continue ត្រូវបានប្រើសម្រាប់រំលងការដំណើរការបច្ចុប្បន្ន និងបន្តទៅកាន់ Iteration បន្ទាប់។ ឧទាហរណ៍៖
            </p>
            <pre class="language-c"><code>
for (int i = 0; i &lt; 10; i++) {
    if (i == 5) {
        break; // បញ្ឈប់ Loop នៅពេល i ស្មើនឹង 5
    }
    if (i % 2 == 0) {
        continue; // រំលងការដំណើរការបច្ចុប្បន្ន បើ i ជាចំនួនគត់
    }
    // កូដដែលត្រូវបានដំណើរការបើ i ជាចំនួនក្រហម
}
            </code></pre>

            <!-- Lesson6 -->
            <h2 id="lesson6" style="color: rgb(231, 90, 2);">មេរៀនទី 6: Functions</h2>
            <h2 id="point6.1">6.1 Function គឺអ្វី?</h2>
            <p>
                Function គឺជាក្រុមនៃកូដដែលអាចត្រូវបានហៅឲ្យដំណើរការជាបន្តបន្ទាប់។ វាអនុញ្ញាតឲ្យអ្នកបែងចែកកម្មវិធីរបស់អ្នកទៅជាផ្នែកតូចៗ ដែលអាចប្រើឡើងវិញបាន និងងាយស្រួលគ្រប់គ្រង។ នៅក្នុង C អ្នកអាចបង្កើត Function ដោយប្រើ Syntax ដូចខាងក្រោម៖
            </p>
            <pre class="language-c"><code>
return_type function_name(parameter_list) {
    // កូដរបស់ Function
    return value; // បើ return_type មិនមែន void
}
            </code></pre>
            <h2 id="point6.2">6.2 ការបង្កើត និងការហៅ Function</h2>
            <p>
                ដើម្បីបង្កើត Function អ្នកត្រូវបញ្ជាក់ return type, function name, និង parameter list (បើមាន)។ បន្ទាប់មក អ្នកអាចហៅ Function ដោយប្រើឈ្មោះរបស់វា និងផ្តល់អាគុយម៉ង់ (arguments) ដែលត្រូវបានទាមទារ។ ឧទាហរណ៍៖
            </p>
            <pre class="language-c"><code>
// ការបង្កើត Function
int add(int a, int b) {
    return a + b;
}

// ការហៅ Function
int result = add(5, 3); // result នឹងមានតម្លៃ   
8
            </code></pre>
            <h2 id="point6.3">6.3 Parameter និង Return Value</h2>
            <p>
                Parameter គឺជាតម្លៃដែលត្រូវបានផ្តល់ឲ្យ Function នៅពេលហៅវា។ Return Value គឺជាតម្លៃដែល Function បញ្ជូនត្រឡប់ទៅកាន់កូដដែលហៅវា។ នៅក្នុង C អ្នកអាចមានច្រើន Parameters និង Return Value មួយតែប៉ុណ្ណោះ។ ឧទាហរណ៍៖
            </p>
            <pre class="language-c"><code>
// Function with multiple parameters and return value
float calculateArea(float radius) {
    return 3.14 * radius * radius; // Return the area of a circle
}
            </code></pre>
            <h2 id="point6.4">6.4 Recursion</h2>
            <p>
                Recursion គឺជាការប្រើប្រាស់ Function ដើម្បីហៅខ្លួនឯង។ វាអាចប្រើសម្រាប់ដោះស្រាយបញ្ហាដែលអាចបែងចែកបានជាបញ្ហាតូចៗ។ នៅក្នុង C អ្នកអាចបង្កើត Function Recursive ដោយធ្វើឲ្យវាហៅខ្លួនឯងនៅក្នុងកូដរបស់វា។ ឧទាហរណ៍៖
            </p>
            <pre class="language-c"><code>
// Function to calculate factorial using recursion
int factorial(int n) {
    if (n == 0) {
        return 1; // Base case: factorial of 0 is 1
    } else {
        return n * factorial(n - 1); // Recursive case
    }
}
            </code></pre>

            <!-- Lesson7 -->
            <h2 id="lesson7" style="color: rgb(231, 90, 2);">មេរៀនទី 7: Arrays​ & Strings</h2>
            <h2 id="point7.1">7.1 Array គឺអ្វី?</h2>
            <p>
                Array គឺជាក្រុមនៃតម្លៃដែលមានប្រភេទដូចគ្នា ដែលត្រូវបានផ្ទុកនៅក្នុងអង្គចងចាំជាប់គ្នា។ វាអនុញ្ញាតឲ្យអ្នកផ្ទុក និងគ្រប់គ្រងតម្លៃច្រើនក្នុងអថេរតែមួយ។ នៅក្នុង C អ្នកអាចបង្កើត Array ដោយប្រើ Syntax ដូចខាងក្រោម៖
            </p>
            <pre class="language-c"><code>
data_type array_name[array_size];
            </code></pre>
            <h2>7.2 ការបង្កើត និងការប្រើប្រាស់ Array</h2>
            <p>
                ដើម្បីបង្កើត Array អ្នកត្រូវបញ្ជាក់ប្រភេទទិន្នន័យ, ឈ្មោះ Array, និងទំហំរបស់វា។ បន្ទាប់មក អ្នកអាចចូលដំណើរការតម្លៃក្នុង Array ដោយប្រើ Index (ចាប់ផ្តើមពី 0)។ ឧទាហរណ៍៖
            </p>
            <pre class="language-c"><code>
// ការបង្កើត Array
int numbers[5];
// ការប្រើប្រាស់ Array
numbers[0] = 10;
numbers[1] = 20;
numbers[2] = 30;
numbers[3] = 40;
numbers[4] = 50;
            </code></pre>
            <h2 id="point7.3">7.3 String គឺអ្វី?</h2>
            <p>
                String គឺជាក្រុមនៃតួអក្សរ ដែលត្រូវបានផ្ទុកនៅក្នុងអង្គចងចាំជាប់គ្នា។ វាអនុញ្ញាតឲ្យអ្នកផ្ទុក និងគ្រប់គ្រងអត្ថបទក្នុងកម្មវិធីរបស់អ្នក។ នៅក្នុង C អ្នកអាចបង្កើត String ដោយប្រើ Array of Characters (char array) ឬ Pointer to Character (char pointer)។ ឧទាហរណ៍៖
            </p>
            <pre class="language-c"><code>
// String using char array
char name[20] = "John Doe";
// String using char pointer
char *greeting = "Hello, World!";
            </code></pre>
            <h2 id="point7.4">7.4 ការបង្កើត និងការប្រើប្រាស់ String</h2>
            <p>
                ដើម្បីបង្កើត String អ្នកអាចប្រើ char array ឬ char pointer ដូចដែលបានបង្ហាញខាងលើ។ បន្ទាប់មក អ្នកអាចចូលដំណើរការតួអក្សរនៅក្នុង String ដោយប្រើ Index (ចាប់ផ្តើមពី 0)។ ឧទាហរណ៍៖
            </p>
            <pre class="language-c"><code>

// ការបង្កើត String
char name[20] = "John Doe";
// ការប្រើប្រាស់ String
printf("Name: %s\n", name); // បង្ហាញ String
            </code></pre>

            <!-- Lesson8 -->
            <h2 id="lesson8" style="color: rgb(231, 90, 2);">មេរៀនទី 8: Pointers</h2>
            <h2 id="point8.1">8.1 Pointer គឺអ្វី?</h2>
            <p>
                Pointer គឺជាអថេរដែលផ្ទុកអាសយដ្ឋាន (address) នៃអថេរផ្សេងទៀត។ វាអនុញ្ញាតឲ្យអ្នកចូលដំណើរការនិងគ្រប់គ្រងអង្គចងចាំដោយផ្ទាល់។ នៅក្នុង C អ្នកអាចបង្កើត Pointer ដោយប្រើ Syntax ដូចខាងក្រោម៖
            </p>
            <pre class="language-c"><code>
data_type *pointer_name;
            </code></pre>
            <h2 id="point8.2">8.2 ការបង្កើត និងការប្រើប្រាស់ Pointer</h2>
            <p>
                ដើម្បីបង្កើត Pointer អ្នកត្រូវបញ្ជាក់ប្រភេទទិន្នន័យ និងឈ្មោះ Pointer។ បន្ទាប់មក អ្នកអាចផ្ទុកអាសយដ្ឋានរបស់អថេរផ្សេងទៀតទៅក្នុង Pointer ដោយប្រើ & (address-of operator) និងចូលដំណើរការតម្លៃដែល Pointer បង្ហាញដោយប្រើ * (dereference operator)។ ឧទាហរណ៍៖
            </p>
            <pre class="language-c"><code>
int x = 10;
int *ptr = &x; // បង្កើត Pointer និងផ្ទុកអាសយដ្ឋានរបស់ x
printf("Value of x: %d\n", *ptr); // ចូលដំណើរការតម្លៃរបស់ x តាមរយៈ Pointer
            </code></pre>
            <h2 id="point8.3">8.3 Pointer និង Array</h2>
            <p>
                Pointer និង Array មានទំនាក់ទំនងជិតស្និទ្ធគ្នា នៅក្នុង C អ្នកអាចប្រើ Pointer ដើម្បីចូលដំណើរការតម្លៃនៅក្នុង Array។ ឧទាហរណ៍៖
            </p>
            <pre class="language-c"><code>
int numbers[5] = {10, 20, 30, 40, 50};
int *ptr = numbers; // បង្កើត Pointer និងផ្ទុកអាសយដ្ឋាននៃ Array
for (int i = 0; i < 5; i++) {
    printf("%d ", *ptr); // ចូលដំណើរការតម្លៃនៅក្នុង Array តាមរយៈ Pointer
    ptr++; // បន្ថែម Pointer ដើម្បីចូលដំណើរការតម្លៃបន្ទាប់
}
            </code></pre>
            <h2 id="point8.4">8.4 Pointer និង Function</h2>
            <p>
                Pointer អាចត្រូវបានប្រើសម្រាប់ផ្ទេរតម្លៃទៅកាន់ Function និងទទួលតម្លៃត្រឡប់ពី Function។ វាអនុញ្ញាតឲ្យអ្នកបង្កើត Function ដែលអាចផ្លាស់ប្ដូរតម្លៃនៅក្នុងកូដដែលហៅវា។ ឧទាហរណ៍៖
            </p>
            <pre class="language-c"><code>
void swap(int *a, int *b) {
    int temp = *a; // ស្តុកតម្លៃរបស់ a ទៅក្នុង temp
    *a = *b; // ផ្លាស់ប្ដូរតម្លៃរបស់ a ជា b
    *b = temp; // ផ្លាស់ប្ដូរតម្លៃរបស់ b ជា temp
}
int main() {
    int x = 5, y = 10;
    printf("Before swap: x = %d, y = %d\n", x, y);
    swap(&x, &y); // ហៅ Function swap ដោយផ្តល់អាសយដ្ឋានរបស់ x និង y
    printf("After swap: x = %d, y = %d\n", x, y);
    return 0;
}
            </code></pre>

            <!-- Lesson9 -->
            <h2 id="lesson9" style="color: rgb(231, 90, 2);">មេរៀនទី 9: Structure & Union</h2>
            <h2 id="point9.1">9.1 Structure គឺអ្វី?</h2>
            <p>
                Structure គឺជាប្រភេទទិន្នន័យដែលអនុញ្ញាតឲ្យអ្នកបង្កើត Data Type ផ្ទាល់ខ្លួន ដែលអាចផ្ទុកតម្លៃប្រភេទផ្សេងៗបាន។ វាអនុញ្ញាតឲ្យអ្នកបង្កើត Object ដែលមានលក្ខណៈ និងអត្ថន័យជាក់លាក់។ នៅក្នុង C អ្នកអាចបង្កើត Structure ដោយប្រើ Syntax ដូចខាងក្រោម៖
            </p>
            <pre class="language-c"><code>
struct structure_name {
    data_type member1;
    data_type member2;
    // អ្នកអាចបន្ថែមសមាសធាតុផ្សេងទៀតបាន
};
            </code></pre>
            <h2 id="point9.2">9.2 ការបង្កើត និងការប្រើប្រាស់ Structure</h2>
            <p>
                ដើម្បីបង្កើត Structure អ្នកត្រូវបញ្ជាក់ឈ្មោះ Structure និងសមាសធាតុរបស់វា។ បន្ទាប់មក អ្នកអាចបង្កើត Variable ដែលមានប្រភេទជា Structure និងចូលដំណើរការសមាសធាតុរបស់វា។ ឧទាហរណ៍៖
            </p>
            <pre class="language-c"><code>
struct Student {
    char name[50];
    int age;
    float gpa;
};
struct Student student1; // បង្កើត Variable student1 ដែលមានប្រភេទជា Structure Student
strcpy(student1.name, "Alice"); // កំណត់ឈ្មោះរបស់ student1
student1.age = 20; // កំណត់អាយុរបស់ student1
student1.gpa = 3.75; // កំណត់ GPA របស់ student1

printf("Name: %s\n", student1.name); // បង្ហាញឈ្មោះរបស់ student1
printf("Age: %d\n", student1.age); // បង្ហាញអាយុរបស់ student1
printf("GPA: %.2f\n", student1.gpa); // បង្ហាញ GPA របស់ student1
            </code></pre>
            <h2>9.3 Union គឺអ្វី?</h2>
            <p>
                Union គឺជាប្រភេទទិន្នន័យដែលអនុញ្ញាតឲ្យអ្នកបង្កើត Data Type ផ្ទាល់ខ្លួន ដែលអាចផ្ទុកតម្លៃប្រភេទផ្សេងៗបាន ប៉ុន្តែតម្លៃទាំងអស់នៃសមាសធាតុរបស់វា ត្រូវបានផ្ទុកនៅក្នុងអង្គចងចាំតែមួយ។ វាអនុញ្ញាតឲ្យអ្នកបង្កើត Object ដែលអាចមានលក្ខណៈ និងអត្ថន័យជាក់លាក់ ប៉ុន្តែតម្លៃរបស់វា អាចផ្លាស់ប្ដូរបាន។ នៅក្នុង C អ្នកអាចបង្កើត Union ដោយប្រើ Syntax ដូចខាងក្រោម៖
            </p>
            <pre class="language-c"><code>
union union_name {
    data_type member1;
    data_type member2;
    // អ្នកអាចបន្ថែមសមាសធាតុផ្សេងទៀតបាន
};
            </code></pre>
            <h2 id="point9.4">9.4 ការបង្កើត និងការប្រើប្រាស់ Union</h2>
            <p>
                ដើម្បីបង្កើត Union អ្នកត្រូវបញ្ជាក់ឈ្មោះ Union និងសមាសធាតុរបស់វា។ បន្ទាប់មក អ្នកអាចបង្កើត Variable ដែលមានប្រភេទជា Union និងចូលដំណើរការសមាសធាតុរបស់វា។ ឧទាហរណ៍៖
            </p>
            <pre class="language-c"><code>

union Data {
    int i;
    float f;
    char str[20];
};
union Data data; // បង្កើត Variable data ដែលមានប្រភេទជា Union Data
data.i = 10; // កំណត់តម្លៃរបស់ i
printf("Data.i: %d\n", data.i); // បង្ហាញតម្លៃរបស់ i
data.f = 3.14; // កំណត់តម្លៃរបស់ f
printf("Data.f: %.2f\n", data.f); // បង្ហាញតម្លៃរបស់ f
strcpy(data.str, "Hello"); // កំណត់តម្លៃរបស់ str
printf("Data.str: %s\n", data.str); // បង្ហាញតម្លៃរបស់ str
            </code></pre>

            <!-- Lesson10 -->
            <h2 id="lesson10" style="color: rgb(231, 90, 2);">មេរៀនទី 10: File I/O</h2>
            <h2 id="point10.1">10.1 File I/O គឺអ្វី?</h2>
            <p>
                File I/O (Input/Output) គឺជាការប្រើប្រាស់សម្រាប់អាន និងសរសេរ ទិន្នន័យទៅកាន់ឯកសារ (file) នៅក្នុងកម្មវិធីរបស់អ្នក។ វាអនុញ្ញាតឲ្យអ្នកផ្ទុក និងទាញយកទិន្នន័យពីឯកសារ ដើម្បីអោយកម្មវិធីរបស់អ្នកមានភាពបត់បែន និងអាចរក្សាទិន្នន័យបានយូរ។ នៅក្នុង C អ្នកអាចប្រើ File I/O ដោយប្រើ Functions ដែលមាននៅក្នុង stdio.h library ដូចជា fopen(), fprintf(), fscanf(), fclose() ជាដើម។
            </p>
            <h2 id="point10.2">10.2 ការបង្កើត និងការប្រើប្រាស់ File</h2>
            <p>
                ដើម្បីបង្កើត File អ្នកអាចប្រើ fopen() function ដោយផ្តល់ឈ្មោះឯកសារ និងរបៀប (mode) ដែលអ្នកចង់បើកឯកសារ។ បន្ទាប់មក អ្នកអាចប្រើ fprintf() និង fscanf() functions ដើម្បីសរសេរ និងអាន ទិន្នន័យទៅកាន់ឯកសារ។ នៅចុងក្រោយ អ្នកត្រូវបិទឯកសារ ដោយប្រើ fclose() function។ ឧទាហរណ៍៖
            </p>
            <pre class="language-c"><code>
// ការបង្កើត និងការប្រើប្រាស់ File
FILE *file = fopen("data.txt", "w"); // បង្កើតឯកសារ data.txt សម្រាប់សរសេរ
if (file == NULL) {
    printf("Error opening file!\n");

} else {
    fprintf(file, "Hello, World!\n"); // សរសេរទិន្នន័យទៅកាន់ឯកសារ
    fprintf(file, "This is a file I/O example.\n");
    fclose(file); // បិទឯកសារ
}
            </code></pre>

            <h2 id="point10.3">10.3 ការអាន និងការសរសេរ File</h2>
            <p>
                ដើម្បីអាន File អ្នកអាចប្រើ fopen() function ដោយផ្តល់ឈ្មោះឯកសារ និងរបៀប (mode) "r" សម្រាប់អាន។ បន្ទាប់មក អ្នកអាចប្រើ fscanf() function ដើម្បីអាន ទិន្នន័យពីឯកសារ និងបង្ហាញវា។ នៅចុងក្រោយ អ្នកត្រូវបិទឯកសារ ដោយប្រើ fclose() function។ ឧទាហរណ៍៖
            </p>
            <pre class="language-c"><code>
// ការអាន File
FILE *file = fopen("data.txt", "r"); // បើកឯកសារ data.txt សម្រាប់អាន
if (file == NULL) {
    printf("Error opening file!\n");
} else {  
    char line[100];
    while (fgets(line, sizeof(line), file)) { // អានបន្ទាត់មួយៗពីឯកសារ
        printf("%s", line); // បង្ហាញបន្ទាត់ដែលបានអាន

    }
    fclose(file); // បិទឯកសារ

</code></pre>
            <h2 id = "point10.4">10.4 ការប្រើប្រាស់ File I/O ជាមួយ Data Structures</h2>
            <p>
                អ្នកអាចប្រើ File I/O ជាមួយ Data Structures ដើម្បីសរសេរ និងអាន ទិន្នន័យដែលមានរចនាសម្ព័ន្ធជាក់លាក់។ ឧទាហរណ៍៖
            </p>
            <pre class="language-c"><code>
struct Student {
    char name[50];
    int age;
    float gpa;
};
// សរសេរ Data Structure ទៅកាន់ File
FILE *file = fopen("students.txt", "w");
if (file == NULL) {
    printf("Error opening file!\n");
} else {
    struct Student student1 = {"Alice", 20, 3.75};
    struct Student student2 = {"Bob", 22, 3.50};
    fprintf(file, "%s %d %.2f\n", student1.name, student1
.age, student1.gpa); // សរសេរទិន្នន័យរបស់ student1 ទៅកាន់ឯកសារ
    fprintf(file, "%s %d %.2f\n", student2.name, student2
.age, student2.gpa); // សរសេរទិន្នន័យរបស់ student2 ទៅកាន់ឯកសារ
    fclose(file);
}
// អាន Data Structure ពី File
FILE *file = fopen("students.txt", "r");
if (file == NULL) {
    printf("Error opening file!\n");
} else {
    struct Student student;
    while (fscanf(file, "%s %d %f", student.name, &student.age, &student.gpa) == 3) { // អានទិន្នន័យពីឯកសារ និងផ្ទុកវាទៅក្នុង Structure
        printf("Name: %s, Age: %d, GPA: %.2f\n", student.name, student.age, student.gpa); // បង្ហាញទិន្នន័យដែលបានអាន
    }
    fclose(file);
            </code></pre>






            
        </main>

    </div>
    <!-- End Main Layout  -->
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/prismjs/prism.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-c.min.js"></script>

<script src="{{ asset('assets/js/c_programming.js') }}"></script>
<script src="{{ asset('assets/js/course.js') }}"></script>
@endpush