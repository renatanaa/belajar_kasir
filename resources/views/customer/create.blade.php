@extends('layouts.app')

@section('title', 'Create Customer')
@section('page-title', 'Create Customer')

@section('content')
    <h1>Create Customer</h1>
    <a href="{{ route('customers.index') }}" class="btn btn-secondary mb-3">Back to Customers</a>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('customers.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <label for="contact" class="form-label">Contact</label>
            <input type="text" class="form-control" id="contact" name="contact" value="{{ old('contact') }}">
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control" id="address" name="address" rows="3">{{ old('address') }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create Customer</button>
    </form>
@endsection