@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-dark-900">
    <!-- Landing Navigation -->
    @include('components.landing-nav')

    <!-- Page Content -->
    <main>
        @yield('page-content')
    </main>

    <!-- Footer -->
    @include('components.footer')
</div>
@endsection
