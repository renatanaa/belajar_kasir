<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('customer');

        if ($request->filled('customer_name')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->customer_name . '%');
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $transactions = $query->latest()->paginate(10);

        return view('transaction.index', compact('transactions'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('transaction.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'transaction_date' => 'required|date',
            'payment_method' => 'required|string',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $taxRate = $request->tax_rate ?? 0;

        $totalQuantity = 0;
        $totalPrice = 0;

        foreach ($request->products as $productData) {
            $product = Product::find($productData['product_id']);
            $quantity = $productData['quantity'];
            $subtotal = $product->price * $quantity;
            $totalQuantity += $quantity;
            $totalPrice += $subtotal;
        }

        $totalPrice += $totalPrice * ($taxRate / 100);

        $transaction = Transaction::create([
            'customer_id' => $request->customer_id,
            'transaction_date' => $request->transaction_date,
            'payment_method' => $request->payment_method,
            'tax_rate' => $taxRate,
            'total_quantity' => $totalQuantity,
            'total_price' => $totalPrice,
        ]);

        foreach ($request->products as $productData) {
            $product = Product::find($productData['product_id']);
            $quantity = $productData['quantity'];
            $subtotal = $product->price * $quantity;

            $transaction->details()->create([
                'product_id' => $productData['product_id'],
                'quantity' => $quantity,
                'price' => $product->price,
                'subtotal' => $subtotal,
            ]);
        }

        return redirect()->route('transactions.index')->with('success', 'Transaction created successfully.');
    }

    public function show(Transaction $transaction)
    {
        $transaction->load('customer', 'details.product');
        return view('transaction.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        $customers = Customer::all();
        $products = Product::all();
        $transaction->load('details.product');
        return view('transaction.edit', compact('transaction', 'customers', 'products'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'transaction_date' => 'required|date',
            'payment_method' => 'required|string',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $taxRate = $request->tax_rate ?? 0;

        $totalQuantity = 0;
        $totalPrice = 0;

        foreach ($request->products as $productData) {
            $product = Product::find($productData['product_id']);
            $quantity = $productData['quantity'];
            $subtotal = $product->price * $quantity;
            $totalQuantity += $quantity;
            $totalPrice += $subtotal;
        }

        $totalPrice += $totalPrice * ($taxRate / 100);

        $transaction->update([
            'customer_id' => $request->customer_id,
            'transaction_date' => $request->transaction_date,
            'payment_method' => $request->payment_method,
            'tax_rate' => $taxRate,
            'total_quantity' => $totalQuantity,
            'total_price' => $totalPrice,
        ]);

        $transaction->details()->delete();

        foreach ($request->products as $productData) {
            $product = Product::find($productData['product_id']);
            $quantity = $productData['quantity'];
            $subtotal = $product->price * $quantity;

            $transaction->details()->create([
                'product_id' => $productData['product_id'],
                'quantity' => $quantity,
                'price' => $product->price,
                'subtotal' => $subtotal,
            ]);
        }

        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully.');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully.');
    }
}