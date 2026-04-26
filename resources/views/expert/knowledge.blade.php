@extends('layouts.dashboard')

@section('title', 'Knowledge Base')
@section('page-title', 'Knowledge Base')

@section('page-content')
<div class="text-center py-16">
    <div class="w-20 h-20 rounded-2xl bg-dark-800 flex items-center justify-center mx-auto mb-4">
        <span class="material-symbols-outlined text-4xl text-gray-600">article</span>
    </div>
    <h3 class="text-lg font-semibold text-white mb-2">Knowledge Base</h3>
    <p class="text-gray-500 max-w-md mx-auto">
        Share agricultural articles, tips, and best practices with farmers.
        Features coming soon.
    </p>
</div>
@endsection
