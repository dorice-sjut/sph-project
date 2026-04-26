@extends('layouts.dashboard')

@section('title', 'Messages')
@section('page-title', 'Messages')

@section('page-content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Conversations List -->
    <div class="lg:col-span-1 p-4 rounded-2xl bg-dark-800 border border-dark-700">
        <h3 class="font-semibold text-white mb-4">Conversations</h3>
        @if(count($conversations) > 0)
            <div class="space-y-2">
                @foreach($conversations as $userId => $messages)
                    @php
                        $otherUser = $messages->first()->sender_id === auth()->id()
                            ? $messages->first()->receiver
                            : $messages->first()->sender;
                        $unread = $messages->where('receiver_id', auth()->id())->where('is_read', false)->count();
                    @endphp
                    <a href="{{ route('messages.conversation', $otherUser) }}"
                       class="flex items-center gap-3 p-3 rounded-xl hover:bg-dark-700 transition-colors {{ request()->route('user')?->id == $otherUser->id ? 'bg-dark-700 border border-primary-600/30' : '' }}">
                        <img src="{{ $otherUser->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($otherUser->name) . '&background=10b981&color=fff' }}"
                             alt="{{ $otherUser->name }}"
                             class="w-10 h-10 rounded-lg object-cover">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="font-medium text-white truncate">{{ $otherUser->name }}</p>
                                @if($unread > 0)
                                    <span class="px-2 py-0.5 rounded-full bg-primary-600 text-white text-xs">{{ $unread }}</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 truncate">{{ $messages->last()->content }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <span class="material-symbols-outlined text-3xl text-gray-600 mb-2">chat</span>
                <p class="text-gray-500 text-sm">No conversations yet</p>
            </div>
        @endif
    </div>

    <!-- Empty State -->
    <div class="lg:col-span-2 flex items-center justify-center p-8 rounded-2xl bg-dark-800 border border-dark-700">
        <div class="flex-1 flex flex-col items-center justify-center bg-dark-900 relative overflow-hidden">
            <!-- Subtle gradient background -->
            <div class="absolute inset-0 bg-gradient-to-br from-primary-900/5 via-transparent to-transparent"></div>
            
            <div class="relative z-10 text-center">
                <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-primary-600/20 to-dark-800 flex items-center justify-center mx-auto mb-6 border border-primary-600/30 shadow-lg shadow-primary-600/10">
                    <span class="material-symbols-outlined text-5xl text-primary-400">chat</span>
                </div>
                <h3 class="text-xl font-semibold text-white mb-3">Select a conversation</h3>
                <p class="text-gray-400 max-w-sm mx-auto">Choose a conversation from the list to start messaging with farmers and buyers</p>
            </div>
        </div>
    </div>
</div>
@endsection
