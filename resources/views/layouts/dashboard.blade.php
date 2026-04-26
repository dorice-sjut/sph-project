@extends('layouts.app')

@php
$pageTitle = $__env->yieldContent('page-title', 'Dashboard');
@endphp

@section('content')
<div x-data="{ sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' }" 
     x-init="window.addEventListener('toggle-sidebar', () => sidebarCollapsed = !sidebarCollapsed)"
     class="min-h-screen bg-slate-900 overflow-x-hidden flex">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div :class="sidebarCollapsed ? 'lg:ml-20' : 'lg:ml-64'"
         class="flex-1 flex flex-col min-h-screen overflow-x-hidden bg-slate-900 transition-all duration-300">
        <!-- Top Navigation -->
        @include('components.top-nav', ['pageTitle' => $pageTitle])

        <!-- Page Content -->
        <main class="p-4 lg:p-6 flex-1 bg-slate-900">
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-900/30 border border-emerald-700 text-emerald-300 animate-fade-in">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined">check_circle</span>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-900/30 border border-red-700 text-red-300 animate-fade-in">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined">error</span>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @yield('page-content')
        </main>
    </div>
</div>
@endsection
