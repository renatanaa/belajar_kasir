@extends('layouts.app')

@section('title', 'View Transaction')
@section('page-title', 'Transaction Details')

@section('content')
    <h1>Transaction Details</h1>
    <a href="{{ route('transactions.index') }}" class="btn btn-secondary mb-3">Back to Transactions</a>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Transaction Information</h5>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>ID:</strong> {{ $transaction->id }}</p>
                    <p><strong>Customer:</strong> {{ $transaction->customer->name }}</p>
                    <p><strong>Transaction Date:</strong> {{ $transaction->transaction_date->format('Y-m-d H:i') }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Payment Method:</strong> {{ ucfirst($transaction->payment_method) }}</p>
                    <p><strong>Tax Rate:</strong> {{ $transaction->tax_rate }}%</p>
                    <p><strong>Total Quantity:</strong> {{ $transaction->total_quantity }}</p>
                    <p><strong>Total Price:</strong> Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <h5>Transaction Details</h5>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaction->details as $detail)
                <tr>
                    <td>{{ $detail->product->name }}</td>
                    <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No details found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-warning">Edit</a>
        <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
        </form>
    </div>
@endsection