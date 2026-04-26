@extends('layouts.dashboard')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

@section('page-content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('farmer.products.update', $product) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
            <h3 class="text-lg font-semibold text-white mb-4">Product Information</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Product Name</label>
                    <input type="text" name="name" value="{{ $product->name }}" required
                           class="w-full px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white focus:border-primary-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Category</label>
                    <select name="category" required
                            class="w-full px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white focus:border-primary-500 focus:outline-none">
                        <option value="Grains" {{ $product->category === 'Grains' ? 'selected' : '' }}>Grains</option>
                        <option value="Vegetables" {{ $product->category === 'Vegetables' ? 'selected' : '' }}>Vegetables</option>
                        <option value="Fruits" {{ $product->category === 'Fruits' ? 'selected' : '' }}>Fruits</option>
                        <option value="Legumes" {{ $product->category === 'Legumes' ? 'selected' : '' }}>Legumes</option>
                        <option value="Dairy" {{ $product->category === 'Dairy' ? 'selected' : '' }}>Dairy</option>
                        <option value="Meat" {{ $product->category === 'Meat' ? 'selected' : '' }}>Meat</option>
                        <option value="Other" {{ $product->category === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                    <textarea name="description" rows="4"
                              class="w-full px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white focus:border-primary-500 focus:outline-none">{{ $product->description }}</textarea>
                </div>
            </div>
        </div>

        <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
            <h3 class="text-lg font-semibold text-white mb-4">Pricing & Stock</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Price</label>
                    <input type="number" name="price" step="0.01" value="{{ $product->price }}" required
                           class="w-full px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white focus:border-primary-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Quantity</label>
                    <input type="number" name="quantity" step="0.01" value="{{ $product->quantity }}" required
                           class="w-full px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white focus:border-primary-500 focus:outline-none">
                </div>
            </div>
        </div>

        <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
            <h3 class="text-lg font-semibold text-white mb-4">Status</h3>
            <select name="status" required
                    class="w-full px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white focus:border-primary-500 focus:outline-none">
                <option value="available" {{ $product->status === 'available' ? 'selected' : '' }}>Available</option>
                <option value="reserved" {{ $product->status === 'reserved' ? 'selected' : '' }}>Reserved</option>
                <option value="sold" {{ $product->status === 'sold' ? 'selected' : '' }}>Sold</option>
            </select>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="px-6 py-3 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-semibold transition-all flex items-center gap-2">
                <span class="material-symbols-outlined">save</span>
                Update Product
            </button>
            <a href="{{ route('farmer.products') }}" class="px-6 py-3 rounded-xl border border-dark-600 text-gray-300 hover:text-white hover:border-gray-500 font-semibold transition-all">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
