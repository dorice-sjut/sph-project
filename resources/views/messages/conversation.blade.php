@extends('layouts.dashboard')

@section('title', 'Chat with ' . $user->name)
@section('page-title', 'Messages')

@section('page-content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-[calc(100vh-12rem)]">
    <!-- Contacts List -->
    <div class="hidden lg:block lg:col-span-1 p-4 rounded-2xl bg-dark-800 border border-dark-700 overflow-hidden">
        <h3 class="font-semibold text-white mb-4">Contacts</h3>
        <div class="space-y-2 overflow-y-auto h-full pb-20">
            @foreach($contacts as $contact)
                <a href="{{ route('messages.conversation', $contact) }}"
                   class="flex items-center gap-3 p-3 rounded-xl hover:bg-dark-700 transition-colors {{ $user->id === $contact->id ? 'bg-dark-700 border border-primary-600/30' : '' }}">
                    <img src="{{ $contact->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($contact->name) . '&background=10b981&color=fff' }}"
                         alt="{{ $contact->name }}"
                         class="w-10 h-10 rounded-lg object-cover">
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-white truncate">{{ $contact->name }}</p>
                        <p class="text-xs text-gray-500 capitalize">{{ $contact->role }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Chat Area -->
    <div class="lg:col-span-2 flex flex-col rounded-2xl bg-dark-800 border border-dark-700 overflow-hidden">
        <!-- Header -->
        <div class="flex items-center gap-4 p-4 border-b border-dark-700">
            <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=10b981&color=fff' }}"
                 alt="{{ $user->name }}"
                 class="w-10 h-10 rounded-lg object-cover">
            <div>
                <p class="font-medium text-white">{{ $user->name }}</p>
                <p class="text-xs text-gray-500 capitalize">{{ $user->role }}</p>
            </div>
        </div>

        <!-- Messages -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4">
            @foreach($messages as $message)
                @if($message->sender_id === auth()->id())
                    <!-- Sent Message -->
                    <div class="flex justify-end">
                        <div class="max-w-[70%] p-4 rounded-2xl rounded-tr-sm bg-primary-600 text-white">
                            <p>{{ $message->content }}</p>
                            <span class="text-xs text-primary-200 mt-1 block">{{ $message->created_at->format('H:i') }}</span>
                        </div>
                    </div>
                @else
                    <!-- Received Message -->
                    <div class="flex justify-start">
                        <div class="max-w-[70%] p-4 rounded-2xl rounded-tl-sm bg-dark-700 text-gray-200">
                            <p>{{ $message->content }}</p>
                            <span class="text-xs text-gray-500 mt-1 block">{{ $message->created_at->format('H:i') }}</span>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Input -->
        <div class="p-4 border-t border-dark-700">
            <form method="POST" action="{{ route('messages.conversation', $user) }}" class="flex gap-3">
                @csrf
                <input type="text" name="content" required
                       class="flex-1 px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white placeholder-gray-500 focus:border-primary-500 focus:outline-none"
                       placeholder="Type your message...">
                <button type="submit" class="px-6 py-3 rounded-xl bg-primary-600 hover:bg-primary-700 text-white transition-colors">
                    <span class="material-symbols-outlined">send</span>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
