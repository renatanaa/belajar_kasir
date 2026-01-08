@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <h1>Dashboard</h1>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Transactions</h5>
                    <h3>{{ \App\Models\Transaction::count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Revenue</h5>
                    <h3>Rp {{ number_format(\App\Models\Transaction::sum('total_price'), 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Products Sold</h5>
                    <h3>{{ \App\Models\Transaction::sum('total_quantity') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <h5 class="mt-4">Recent Transactions</h5>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Total Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse(\App\Models\Transaction::with('customer')->latest()->take(5)->get() as $transaction)
                <tr>
                    <td>{{ $transaction->id }}</td>
                    <td>{{ $transaction->customer->name }}</td>
                    <td>{{ $transaction->transaction_date->format('Y-m-d') }}</td>
                    <td>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-info btn-sm">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No transactions yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection