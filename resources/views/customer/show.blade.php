@extends('layouts.app')

@section('title', 'View Customer')
@section('page-title', 'Customer Details')

@section('content')
    <h1>Customer Details</h1>
    <a href="{{ route('customers.index') }}" class="btn btn-secondary mb-3">Back to Customers</a>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Customer Information</h5>
            <p><strong>ID:</strong> {{ $customer->id }}</p>
            <p><strong>Name:</strong> {{ $customer->name }}</p>
            <p><strong>Contact:</strong> {{ $customer->contact ?? 'Not provided' }}</p>
            <p><strong>Address:</strong> {{ $customer->address ?? 'Not provided' }}</p>
            <p><strong>Created At:</strong> {{ $customer->created_at->format('Y-m-d H:i:s') }}</p>
            <p><strong>Updated At:</strong> {{ $customer->updated_at->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning">Edit</a>
        <form action="{{ route('customers.destroy', $customer) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
        </form>
    </div>
@endsection