@extends('layouts.app')

@section('title', 'Edit Transaction')
@section('page-title', 'Edit Transaction')

@section('content')
    <h1>Edit Transaction</h1>
    <a href="{{ route('transactions.index') }}" class="btn btn-secondary mb-3">Back to Transactions</a>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('transactions.update', $transaction) }}" method="POST" id="transaction-form">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="customer_id" class="form-label">Customer</label>
                    <select class="form-control" id="customer_id" name="customer_id" required>
                        <option value="">Select Customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id', $transaction->customer_id) == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="transaction_date" class="form-label">Transaction Date</label>
                    <input type="date" class="form-control" id="transaction_date" name="transaction_date" value="{{ old('transaction_date', $transaction->transaction_date->format('Y-m-d')) }}" required>
                </div>
                <div class="mb-3">
                    <label for="payment_method" class="form-label">Payment Method</label>
                    <select class="form-control" id="payment_method" name="payment_method" required>
                        <option value="">Select Payment Method</option>
                        <option value="cash" {{ old('payment_method', $transaction->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="card" {{ old('payment_method', $transaction->payment_method) == 'card' ? 'selected' : '' }}>Card</option>
                        <option value="transfer" {{ old('payment_method', $transaction->payment_method) == 'transfer' ? 'selected' : '' }}>Transfer</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="tax_rate" class="form-label">Tax Rate (%)</label>
                    <input type="number" step="0.01" min="0" max="100" class="form-control" id="tax_rate" name="tax_rate" value="{{ old('tax_rate', $transaction->tax_rate) }}">
                </div>
            </div>
            <div class="col-md-6">
                <h5>Products</h5>
                <div id="products-container">
                    @foreach($transaction->details as $index => $detail)
                        <div class="product-row mb-3 border p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Product</label>
                                    <select class="form-control product-select" name="products[{{ $index }}][product_id]" required>
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-price="{{ $product->price }}" {{ $detail->product_id == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }} - Rp {{ number_format($product->price, 0, ',', '.') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Quantity</label>
                                    <input type="number" min="1" class="form-control quantity-input" name="products[{{ $index }}][quantity]" value="{{ old('products.' . $index . '.quantity', $detail->quantity) }}" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Subtotal</label>
                                    <input type="text" class="form-control subtotal-display" readonly>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger remove-product" style="margin-top: 32px;">&times;</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-success" id="add-product">Add Product</button>
                <div class="mt-3">
                    <strong>Total: <span id="total-price">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span></strong>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update Transaction</button>
    </form>
@endsection

@section('scripts')
<script>
    let productIndex = parseInt('{{ $transaction->details->count() }}');

    document.getElementById('add-product').addEventListener('click', function() {
        const container = document.getElementById('products-container');
        const newRow = container.querySelector('.product-row').cloneNode(true);
        newRow.querySelectorAll('input, select').forEach(input => {
            if (input.name) {
                input.name = input.name.replace(/\[\d+\]/, '[' + productIndex + ']');
            }
            input.value = input.type === 'number' ? '1' : '';
        });
        newRow.querySelector('.subtotal-display').value = '';
        container.appendChild(newRow);
        productIndex++;
        updateTotals();
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-product')) {
            if (document.querySelectorAll('.product-row').length > 1) {
                e.target.closest('.product-row').remove();
                updateTotals();
            }
        }
    });

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select') || e.target.classList.contains('quantity-input')) {
            updateTotals();
        }
    });

    document.getElementById('tax_rate').addEventListener('input', updateTotals);

    function updateTotals() {
        let total = 0;
        document.querySelectorAll('.product-row').forEach(row => {
            const select = row.querySelector('.product-select');
            const quantity = row.querySelector('.quantity-input').value || 0;
            const price = select.options[select.selectedIndex]?.getAttribute('data-price') || 0;
            const subtotal = price * quantity;
            row.querySelector('.subtotal-display').value = 'Rp ' + subtotal.toLocaleString('id-ID');
            total += subtotal;
        });

        const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
        total += total * (taxRate / 100);

        document.getElementById('total-price').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    updateTotals();
</script>
@endsection