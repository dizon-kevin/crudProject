<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(5);

        return view('products.index', compact('products'))
            ->with('i', ($products->currentPage() - 1) * $products->perPage());
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'details' => 'nullable|string',
            'detail'  => 'nullable|string',
        ]);

        // Accept either 'details' (form) or 'detail' (DB column) and store to DB column 'detail'
        $detail = $request->input('details', $request->input('detail'));

        Product::create([
            'name' => $request->input('name'),
            'detail' => $detail,
        ]);

        return redirect()->route('products.index')
                         ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'details' => 'nullable|string',
            'detail'  => 'nullable|string',
        ]);

        $detail = $request->input('details', $request->input('detail'));

        $product->update([
            'name' => $request->input('name'),
            'detail' => $detail,
        ]);

        return redirect()->route('products.index')
                         ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
                         ->with('success', 'Product deleted successfully.');
    }
}