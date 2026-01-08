@extends('layouts.app')

@section('title', 'Customers')
@section('page-title', 'Customers')

@section('content')
    <h1>Customers</h1>
    <a href="{{ route('customers.create') }}" class="btn btn-primary mb-3">Create Customer</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Contact</th>
                <th>Address</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $customer)
                <tr>
                    <td>{{ $customer->id }}</td>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->contact ?? '-' }}</td>
                    <td>{{ $customer->address ?? '-' }}</td>
                    <td>{{ $customer->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        <a href="{{ route('customers.show', $customer) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('customers.destroy', $customer) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No customers found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection