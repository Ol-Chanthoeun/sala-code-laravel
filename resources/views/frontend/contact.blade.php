@extends('layouts.frontend')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/contact.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')

<section class="contact-page">

    <div class="contact-container">

        <div class="top-section">
            <h1>
                ទំនាក់ទំនង<span class="highlight-text">មកកាន់យើងខ្ញុំ</span>
            </h1>

            <p class="subtitle">
                បើមិត្តអ្នកសិក្សាមានសំណួរ ឬចម្ងល់អីអាចសាកសួរមកកាន់យើងបាន
                តាមរយៈការសរសេរនៅទីនេះ !
            </p>
        </div>
        @if(session('success'))

            <div class="success-message">
                {{ session('success') }}
            </div>

        @endif

        <form action="{{ route('contact.store') }}" method="POST" class="contact-form">
            @csrf

            <div class="form-row">

                <div class="form-group">
                    <label>អ៊ីម៉ែល</label>

                    <input
                        type="email"
                        name="email"
                        placeholder="សរសេរអ៊ីម៉ែលរបស់អ្នក"
                        required
                    />
                </div>

                <div class="form-group">
                    <label>ឈ្មោះ</label>

                    <input
                        type="text"
                        name="name"
                        placeholder="សរសេរឈ្មោះរបស់អ្នក"
                        required
                    />
                </div>

            </div>

            <div class="form-group">
                <label>សារ</label>

                <textarea
                    name="message"
                    rows="5"
                    placeholder="សរសេរសាររបស់អ្នកនៅទីនេះ..."
                    required
                ></textarea>
            </div>

            <button type="submit" class="submit-btn">
                សូមចុចបញ្ជូន
            </button>

        </form>

    </div>

    <div class="info-section-wrapper">

        <div class="blue-background"></div>

        <div class="info-card-container">

            <div class="info-card">

                <div class="info-item">
                    <div class="icon-circle">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>

                    <h3>About</h3>

                    <p>
                        និស្សិតឆ្នាំទី ២ ឆមាសទី ១<br>
                        ជំនាញ ITE នៅ RUPP
                    </p>
                </div>

                <div class="info-item">
                    <div class="icon-circle">
                        <i class="fa-solid fa-phone-volume"></i>
                    </div>

                    <h3>Phone</h3>

                    <p>
                        +855 962796742<br>
                        +855 014577474
                    </p>
                </div>

                <div class="info-item">
                    <div class="icon-circle">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>

                    <h3>Location</h3>

                    <p>
                        សង្កាត់ទឹកថ្លា ខណ្ឌសែនសុខ<br>
                        រាជធានី ភ្នំពេញ
                    </p>
                </div>

            </div>

        </div>

    </div>

</section>

@endsection