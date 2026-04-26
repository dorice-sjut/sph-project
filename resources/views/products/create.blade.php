@extends('layouts.dashboard')

@section('title', 'Add Product')
@section('page-title', 'Add New Product')

@section('page-content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('farmer.products') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
            <h3 class="text-lg font-semibold text-white mb-4">Basic Information</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Product Name</label>
                    <input type="text" name="name" required
                           class="w-full px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white focus:border-primary-500 focus:outline-none"
                           placeholder="e.g., Fresh Maize">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Category</label>
                    <select name="category" required
                            class="w-full px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white focus:border-primary-500 focus:outline-none">
                        <option value="">Select category</option>
                        <option value="Grains">Grains</option>
                        <option value="Vegetables">Vegetables</option>
                        <option value="Fruits">Fruits</option>
                        <option value="Legumes">Legumes</option>
                        <option value="Dairy">Dairy</option>
                        <option value="Meat">Meat</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                    <textarea name="description" rows="4"
                              class="w-full px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white focus:border-primary-500 focus:outline-none"
                              placeholder="Describe your product..."></textarea>
                </div>
            </div>
        </div>

        <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
            <h3 class="text-lg font-semibold text-white mb-4">Pricing & Stock</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Price</label>
                    <input type="number" name="price" step="0.01" min="0" required
                           class="w-full px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white focus:border-primary-500 focus:outline-none"
                           placeholder="0.00">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Unit</label>
                    <select name="price_unit" required
                            class="w-full px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white focus:border-primary-500 focus:outline-none">
                        <option value="kg">Per kg</option>
                        <option value="unit">Per unit</option>
                        <option value="bunch">Per bunch</option>
                        <option value="litre">Per litre</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Quantity</label>
                    <input type="number" name="quantity" step="0.01" min="0" required
                           class="w-full px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white focus:border-primary-500 focus:outline-none"
                           placeholder="Available stock">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Quantity Unit</label>
                    <select name="quantity_unit" required
                            class="w-full px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white focus:border-primary-500 focus:outline-none">
                        <option value="kg">kg</option>
                        <option value="unit">units</option>
                        <option value="bunch">bunches</option>
                        <option value="litre">litres</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
            <h3 class="text-lg font-semibold text-white mb-4">Location</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Location</label>
                    <input type="text" name="location" required
                           class="w-full px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white focus:border-primary-500 focus:outline-none"
                           placeholder="e.g., Arusha, Tanzania">
                </div>
            </div>
        </div>

        <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
            <h3 class="text-lg font-semibold text-white mb-4">Additional Details</h3>
            <div class="space-y-4">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_organic" value="1"
                           class="w-5 h-5 rounded border-dark-600 bg-dark-900 text-primary-600">
                    <span class="text-gray-300">This is an organic product</span>
                </label>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Harvest Date</label>
                        <input type="date" name="harvest_date"
                               class="w-full px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white focus:border-primary-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Best Before</label>
                        <input type="date" name="expiry_date"
                               class="w-full px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white focus:border-primary-500 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Images</label>
                    <input type="file" name="images[]" multiple accept="image/*"
                           class="w-full px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white focus:border-primary-500 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-primary-600 file:text-white file:text-sm">
                    <p class="text-xs text-gray-500 mt-2">Upload up to 5 images. Max 2MB each.</p>
                </div>
            </div>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="px-6 py-3 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-semibold transition-all flex items-center gap-2">
                <span class="material-symbols-outlined">save</span>
                Save Product
            </button>
            <a href="{{ route('farmer.products') }}" class="px-6 py-3 rounded-xl border border-dark-600 text-gray-300 hover:text-white hover:border-gray-500 font-semibold transition-all">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
