@extends('layouts.admin')

@section('title', 'Contact Messages')
@section('page-title', 'Contact Messages')
@section('breadcrumb', 'Contacts')

@section('content')

@if(session('success'))
    <div style="
        background:#d4edda;
        color:#155724;
        padding:12px;
        border-radius:8px;
        margin-bottom:20px;
    ">
        {{ session('success') }}
    </div>
@endif

<div class="data-table">

    <div class="table-header">
        <h3>All Contact Messages</h3>
    </div>

    <div class="table-responsive">

        <table>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>NAME</th>
                    <th>EMAIL</th>
                    <th>MESSAGE</th>
                    <th>DATE</th>
                    <th>ACTION</th>
                </tr>
            </thead>

            <tbody>

                @forelse($contacts as $contact)

                    <tr>
                        <td>{{ $contact->id }}</td>

                        <td>{{ $contact->name }}</td>

                        <td>{{ $contact->email }}</td>

                        <td>{{ $contact->message }}</td>

                        <td>
                            {{ $contact->created_at->format('M d, Y h:i A') }}
                        </td>

                        <td>

                            <form action="{{ route('admin.contacts.delete', $contact->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Delete this message?')">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        style="
                                            background:#dc3545;
                                            color:white;
                                            border:none;
                                            padding:8px 12px;
                                            border-radius:5px;
                                            cursor:pointer;
                                        ">
                                    Delete
                                </button>

                            </form>

                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="6" style="text-align:center;">
                            No messages found.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection