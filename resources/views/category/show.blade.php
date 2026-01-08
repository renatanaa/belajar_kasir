@extends('layouts.app')

@section('title', 'View Category')
@section('page-title', 'Category Details')

@section('content')
    <h1>Category Details</h1>
    <a href="{{ route('categories.index') }}" class="btn btn-secondary mb-3">Back to Categories</a>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Category Information</h5>
            <p><strong>ID:</strong> {{ $category->id }}</p>
            <p><strong>Name:</strong> {{ $category->name }}</p>
            <p><strong>Created At:</strong> {{ $category->created_at->format('Y-m-d H:i:s') }}</p>
            <p><strong>Updated At:</strong> {{ $category->updated_at->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">Edit</a>
        <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
        </form>
    </div>
@endsection