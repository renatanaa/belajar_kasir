@extends('layouts.app')

@section('title', 'View Product')
@section('page-title', 'Product Details')

@section('content')
    <h1>Product Details</h1>
    <a href="{{ route('products.index') }}" class="btn btn-secondary mb-3">Back to Products</a>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Product Information</h5>
            <p><strong>ID:</strong> {{ $product->id }}</p>
            <p><strong>Name:</strong> {{ $product->name }}</p>
            <p><strong>Category:</strong> {{ $product->category->name ?? 'N/A' }}</p>
            <p><strong>Price:</strong> Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            <p><strong>Quantity:</strong> {{ $product->quantity }}</p>
            <p><strong>Created At:</strong> {{ $product->created_at->format('Y-m-d H:i:s') }}</p>
            <p><strong>Updated At:</strong> {{ $product->updated_at->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">Edit</a>
        <form action="{{ route('products.destroy', $product) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
        </form>
    </div>
@endsection