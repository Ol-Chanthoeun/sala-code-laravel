@extends('layouts.admin')

@section('content')

<h1>Contact Messages</h1>

<table>

    <thead>

        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Message</th>
        </tr>

    </thead>

    <tbody>

        @foreach ($contacts as $contact)

            <tr>

                <td>{{ $contact->id }}</td>

                <td>{{ $contact->name }}</td>

                <td>{{ $contact->email }}</td>

                <td>{{ $contact->message }}</td>

            </tr>

        @endforeach

    </tbody>

</table>

@endsection