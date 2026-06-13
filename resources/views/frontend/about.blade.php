@extends('layouts.frontend')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/about.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@section('content')

<section class="about">
    <h1 class="title">
        អំពី<span>ធីម</span>ការងារ
    </h1>

    <p class="My-team">
        ក្រុម SALA CODE គឺជាក្រុមសិស្សនិស្សិតដែលបង្កើតគេហទំព័រ (website)
        និងប្រព័ន្ធផ្សេងៗ ដើម្បីបម្រើការសិក្សា និងការអនុវត្តជាក់ស្តែង។
    </p>

    <div class="container-grid">
        <div class="picture">
            <img class="workteam-picture" src="{{ asset('assets/images/discussion.png') }}">
        </div>

        <div class="desrciption">
            <h1 class="our-team">Our Team</h1>

            <p class="team">
                A team is a group of people who work together, share ideas,
                and support each other to achieve a common goal.
            </p>

            <div class="black-line"></div>

            <div class="text">
                <p>គុណលក្ខណៈសំខាន់ៗរបស់ក្រុមយើង</p>
                <ul>
                    <li><span class="icon">🔄</span>ការសហការ : ធ្វើការជាមួយគ្នាដើម្បីសម្រេចគោលដៅ ។</li>
                    <li><span class="icon">🎯</span>គោលដៅរួម : មានគោលដៅដូចគ្នា ។</li>
                    <li><span class="icon">📋</span>ទំនួលខុសត្រូវ : ទទួលខុសត្រូវលើការងាររបស់ខ្លួន ។</li>
                    <li><span class="icon">🤝</span>ការចែករំលែកចំណេះដឹង : ជួយគ្នារៀននិងអភិវឌ្ឍន៍ជំនាញ ។</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="introduce-our-team">
    <h1 class="team-member">សមាជិកក្រុមការងារ</h1>

    <div class="card-container">

        @php
            $members = [
                ['name' => 'អុល ចាន់ធឿន', 'image' => 'Thoeun(1).png', 'roles' => ['Frontend Developer', 'UX/UI Designer']],
                ['name' => 'អ៊ុំ បញ្ញារិទ្ធិ', 'image' => 'Rith.png', 'roles' => ['Frontend Developer']],
                ['name' => 'ផៃ សីលា', 'image' => 'Seyla (1).png', 'roles' => ['Frontend Developer']],
                ['name' => 'ផៃ រ៉ានុត', 'image' => 'Ranut.png', 'roles' => ['Frontend Developer']],
            ];
        @endphp

        @foreach ($members as $member)
            <div class="card">
                <img src="{{ asset('assets/images/' . $member['image']) }}">
                <h3 class="name">{{ $member['name'] }}</h3>

                @foreach ($member['roles'] as $role)
                    <p>{{ $role }}</p>
                @endforeach

                <div class="icon">
                    <a href="https://www.facebook.com/" target="_blank">
                        <img src="{{ asset('assets/images/Facebook about.png') }}" alt="Facebook">
                    </a>

                    <a href="https://www.telegram.com/" target="_blank">
                        <img class="telegram" src="{{ asset('assets/images/telegram about.png') }}" alt="Telegram">
                    </a>

                    <a href="https://www.youtube.com/" target="_blank">
                        <img src="{{ asset('assets/images/youtube about.jpeg') }}" alt="Youtube">
                    </a>

                    <a href="https://www.tiktok.com/" target="_blank">
                        <img src="{{ asset('assets/images/tiktok about02.png') }}" alt="Tiktok">
                    </a>
                </div>

                <button class="Buttom">Read More</button>
            </div>
        @endforeach

    </div>
</section>

@endsection