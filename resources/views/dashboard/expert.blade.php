@extends('layouts.dashboard')

@section('title', 'Expert Dashboard')
@section('page-title', 'Dashboard')

@section('page-content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-primary-600/20 flex items-center justify-center">
                <span class="material-symbols-outlined text-primary-400">agriculture</span>
            </div>
            <span class="text-xs text-gray-500">Consultations</span>
        </div>
        <div class="text-3xl font-bold text-white">{{ $stats['consultations'] }}</div>
        <p class="text-sm text-gray-500 mt-1">Total sessions</p>
    </div>

    <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-accent-blue/20 flex items-center justify-center">
                <span class="material-symbols-outlined text-accent-blue">star</span>
            </div>
            <span class="text-xs text-gray-500">Rating</span>
        </div>
        <div class="text-3xl font-bold text-white">{{ $stats['rating'] }}</div>
        <p class="text-sm text-gray-500 mt-1">Average</p>
    </div>

    <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-accent-purple/20 flex items-center justify-center">
                <span class="material-symbols-outlined text-accent-purple">chat</span>
            </div>
            <span class="text-xs text-gray-500">Messages</span>
        </div>
        <div class="text-3xl font-bold text-white">{{ $stats['messages'] }}</div>
        <p class="text-sm text-gray-500 mt-1">Unread</p>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
    <a href="{{ route('expert.consultations') }}" class="group p-6 rounded-2xl bg-primary-600/10 border border-primary-600/30 hover:bg-primary-600/20 transition-all">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-white mb-1">Consultations</h3>
                <p class="text-sm text-gray-400">Manage farmer consultation requests</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-primary-600/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-primary-400">agriculture</span>
            </div>
        </div>
    </a>
    <a href="{{ route('expert.knowledge') }}" class="group p-6 rounded-2xl bg-accent-blue/10 border border-accent-blue/30 hover:bg-accent-blue/20 transition-all">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-white mb-1">Knowledge Base</h3>
                <p class="text-sm text-gray-400">Share articles and farming tips</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-accent-blue/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-accent-blue">article</span>
            </div>
        </div>
    </a>
</div>

<!-- Recent Activity -->
<div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
    <div class="text-center py-12">
        <div class="w-16 h-16 rounded-xl bg-dark-700 flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-3xl text-gray-500">school</span>
        </div>
        <h3 class="text-lg font-semibold text-white mb-2">Welcome, Expert!</h3>
        <p class="text-gray-500 max-w-md mx-auto">
            Your expert dashboard helps you manage consultations and share agricultural knowledge with farmers.
        </p>
    </div>
</div>
@endsection
