@extends('layouts.dashboard')

@section('title', 'Users Management')
@section('page-title', 'Users')

@section('page-content')
<!-- Filters -->
<div class="p-4 rounded-2xl bg-dark-800 border border-dark-700 mb-6">
    <form method="GET" class="flex flex-col md:flex-row gap-4">
        <div class="relative flex-1">
            <span class="material-symbols-outlined absolute left-4 top-3 text-gray-500">search</span>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="w-full pl-12 pr-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white placeholder-gray-500 focus:border-primary-500 focus:outline-none"
                   placeholder="Search users...">
        </div>
        <select name="role" class="px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white focus:border-primary-500 focus:outline-none">
            <option value="">All Roles</option>
            @foreach($roles as $role)
                <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-6 py-3 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-medium transition-all">
            Filter
        </button>
    </form>
</div>

<!-- Users Table -->
<div class="bg-dark-800 border border-dark-700 rounded-2xl overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-dark-700">
                <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">User</th>
                <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Role</th>
                <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Location</th>
                <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Joined</th>
                <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr class="border-b border-dark-700 last:border-0 hover:bg-dark-700/30 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=10b981&color=fff' }}"
                                 alt="{{ $user->name }}"
                                 class="w-10 h-10 rounded-lg object-cover">
                            <div>
                                <p class="font-medium text-white">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full bg-dark-700 text-gray-300 text-sm capitalize">{{ $user->role }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-gray-400">{{ $user->location ?? 'N/A' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-gray-400">{{ $user->created_at->format('M d, Y') }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm {{ $user->is_active ? 'bg-primary-600/20 text-primary-400' : 'bg-gray-500/20 text-gray-400' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $users->links() }}
</div>
@endsection
