@extends('layouts.dashboard')

@section('title', 'Supply Orders')
@section('page-title', 'Orders to Fulfill')

@section('page-content')
<div class="text-center py-16">
    <div class="w-20 h-20 rounded-2xl bg-dark-800 flex items-center justify-center mx-auto mb-4">
        <span class="material-symbols-outlined text-4xl text-gray-600">local_shipping</span>
    </div>
    <h3 class="text-lg font-semibold text-white mb-2">Supply Orders</h3>
    <p class="text-gray-500 max-w-md mx-auto">
        View and manage orders that need to be fulfilled.
        Features coming soon.
    </p>
</div>
@endsection
