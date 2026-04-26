@extends('layouts.dashboard')

@section('page-title', 'Agro AI')

@section('content')
<div class="h-[calc(100vh-120px)] flex flex-col">
    <!-- Header -->
    <div class="flex items-center justify-between p-4 rounded-t-2xl bg-dark-800 border-x border-t border-dark-700">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary-600 to-primary-700 flex items-center justify-center shadow-lg shadow-primary-600/20">
                <span class="material-symbols-outlined text-white text-xl">smart_toy</span>
            </div>
            <div>
                <h1 class="font-semibold text-white">Agro AI</h1>
                <div class="flex items-center gap-1 text-xs text-green-400">
                    <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                    Online
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <!-- Fullscreen Toggle -->
            <button onclick="toggleFullscreen()" 
                    id="fullscreen-btn"
                    class="p-2 rounded-xl hover:bg-dark-700 text-gray-400 hover:text-white transition-colors"
                    title="Toggle Fullscreen">
                <span class="material-symbols-outlined" id="fullscreen-icon">fullscreen</span>
            </button>
            <button onclick="document.getElementById('clear-modal').classList.remove('hidden')" 
                    class="p-2 rounded-xl hover:bg-dark-700 text-gray-400 hover:text-white transition-colors"
                    title="Clear chat history">
                <span class="material-symbols-outlined">delete</span>
            </button>
            <a href="{{ route('farmer.dashboard') }}" class="p-2 rounded-xl hover:bg-dark-700 text-gray-400 hover:text-white transition-colors">
                <span class="material-symbols-outlined">close</span>
            </a>
        </div>
    </div>

    <!-- Chat Messages Area -->
    <div id="chat-container" class="flex-1 overflow-y-auto p-4 bg-dark-900 border-x border-dark-700 space-y-4">
        @foreach($chats as $chat)
            @if($chat->type === 'ai')
                <!-- AI Message -->
                <div class="flex items-start gap-3 ai-message animate-fade-in">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-600 to-primary-700 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-white text-sm">smart_toy</span>
                    </div>
                    <div class="max-w-[80%]">
                        <div class="bg-dark-800 border border-dark-700 rounded-2xl rounded-tl-sm p-4 shadow-sm">
                            <div class="text-gray-200 text-sm leading-relaxed whitespace-pre-line">{{ $chat->response }}</div>
                        </div>
                        <span class="text-xs text-gray-500 ml-2 mt-1 block">{{ $chat->created_at->format('H:i') }}</span>
                    </div>
                </div>
            @else
                <!-- User Message -->
                <div class="flex items-start gap-3 justify-end user-message animate-fade-in">
                    <div class="max-w-[80%] order-1">
                        <div class="bg-gradient-to-r from-primary-600 to-primary-700 rounded-2xl rounded-tr-sm p-4 shadow-lg shadow-primary-600/10">
                            @if($chat->has_image && $chat->image_path)
                                <div class="mb-2">
                                    <img src="{{ $chat->image_path }}" alt="Uploaded image" 
                                         class="max-w-[200px] max-h-[150px] rounded-lg object-cover cursor-pointer hover:opacity-90 transition-opacity"
                                         onclick="window.open('{{ $chat->image_path }}', '_blank')">
                                </div>
                            @endif
                            @if($chat->message)
                                <div class="text-white text-sm leading-relaxed">{{ $chat->message }}</div>
                            @endif
                        </div>
                        <span class="text-xs text-gray-500 mr-2 mt-1 block text-right">{{ $chat->created_at->format('H:i') }}</span>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-dark-700 flex items-center justify-center flex-shrink-0 order-2">
                        <span class="material-symbols-outlined text-gray-400 text-sm">person</span>
                    </div>
                </div>
            @endif
        @endforeach

        <!-- Typing Indicator (Hidden by default) -->
        <div id="typing-indicator" class="hidden flex items-start gap-3 ai-message">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-600 to-primary-700 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-white text-sm">smart_toy</span>
            </div>
            <div class="bg-dark-800 border border-dark-700 rounded-2xl rounded-tl-sm px-4 py-3">
                <div class="flex items-center gap-1">
                    <div class="w-2 h-2 rounded-full bg-gray-400 animate-bounce" style="animation-delay: 0s"></div>
                    <div class="w-2 h-2 rounded-full bg-gray-400 animate-bounce" style="animation-delay: 0.2s"></div>
                    <div class="w-2 h-2 rounded-full bg-gray-400 animate-bounce" style="animation-delay: 0.4s"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="px-4 py-2 bg-dark-900 border-x border-dark-700 flex gap-2 overflow-x-auto">
        <button onclick="quickAsk('My maize has yellow spots on leaves. What disease is this?')" 
                class="px-4 py-2 rounded-full bg-dark-800 border border-dark-700 text-xs text-gray-300 hover:bg-dark-700 hover:text-white transition-colors whitespace-nowrap">
            🔍 Disease ID
        </button>
        <button onclick="quickAsk('Best fertilizer for coffee plantation?')" 
                class="px-4 py-2 rounded-full bg-dark-800 border border-dark-700 text-xs text-gray-300 hover:bg-dark-700 hover:text-white transition-colors whitespace-nowrap">
            🧪 Fertilizer
        </button>
        <button onclick="quickAsk('When should I harvest my maize?')" 
                class="px-4 py-2 rounded-full bg-dark-800 border border-dark-700 text-xs text-gray-300 hover:bg-dark-700 hover:text-white transition-colors whitespace-nowrap">
            🌾 Harvest Time
        </button>
        <button onclick="quickAsk('How to control army worms?')" 
                class="px-4 py-2 rounded-full bg-dark-800 border border-dark-700 text-xs text-gray-300 hover:bg-dark-700 hover:text-white transition-colors whitespace-nowrap">
            🐛 Pest Control
        </button>
        <button onclick="quickAsk('Current market price for beans?')" 
                class="px-4 py-2 rounded-full bg-dark-800 border border-dark-700 text-xs text-gray-300 hover:bg-dark-700 hover:text-white transition-colors whitespace-nowrap">
            💰 Market Prices
        </button>
    </div>

    <!-- Input Area -->
    <div class="p-4 rounded-b-2xl bg-dark-800 border border-dark-700">
        <form id="chat-form" action="{{ route('farmer.ai.chat') }}" method="POST" enctype="multipart/form-data" class="flex items-end gap-2">
            @csrf
            
            <!-- Image Upload -->
            <div class="relative">
                <input type="file" id="image-input" name="image" accept="image/*" class="hidden" onchange="previewImage(this)">
                <button type="button" onclick="document.getElementById('image-input').click()" 
                        class="p-3 rounded-xl bg-dark-700 text-gray-400 hover:text-white hover:bg-dark-600 transition-colors"
                        title="Upload image for diagnosis">
                    <span class="material-symbols-outlined">add_photo_alternate</span>
                </button>
            </div>

            <!-- Image Preview -->
            <div id="image-preview" class="hidden relative">
                <img id="preview-img" class="w-12 h-12 rounded-lg object-cover border border-dark-600">
                <button type="button" onclick="clearImage()" class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-red-500 text-white text-xs flex items-center justify-center">
                    ×
                </button>
            </div>

            <!-- Text Input -->
            <div class="flex-1 relative">
                <textarea id="message-input" name="message" rows="1" 
                          placeholder="Ask about diseases, pests, fertilizers, irrigation..."
                          class="w-full px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white placeholder-gray-500 focus:border-primary-500 focus:outline-none resize-none overflow-hidden"
                          oninput="autoResize(this)"
                          onkeypress="handleKeyPress(event)"></textarea>
            </div>

            <!-- Send Button -->
            <button type="submit" id="send-btn" 
                    class="p-3 rounded-xl bg-gradient-to-r from-primary-600 to-primary-700 text-white hover:from-primary-500 hover:to-primary-600 transition-all shadow-lg shadow-primary-600/20">
                <span class="material-symbols-outlined">send</span>
            </button>
        </form>
        <div class="mt-2 text-xs text-gray-500 text-center">
            💡 Tip: Upload photos of crop problems for visual diagnosis
        </div>
    </div>
</div>

<!-- Clear History Modal -->
<div id="clear-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700 max-w-sm mx-4">
        <h3 class="text-lg font-semibold text-white mb-2">Clear Chat History?</h3>
        <p class="text-gray-400 text-sm mb-4">This will delete all your conversation history with the AI assistant.</p>
        <div class="flex gap-3">
            <button onclick="document.getElementById('clear-modal').classList.add('hidden')" 
                    class="flex-1 px-4 py-2 rounded-xl bg-dark-700 text-gray-300 hover:bg-dark-600 transition-colors">
                Cancel
            </button>
            <form action="{{ route('farmer.ai.clear') }}" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full px-4 py-2 rounded-xl bg-red-600 text-white hover:bg-red-500 transition-colors">
                    Clear
                </button>
            </form>
        </div>
    </div>
</div>

<style>
/* Custom scrollbar for chat */
#chat-container::-webkit-scrollbar {
    width: 6px;
}
#chat-container::-webkit-scrollbar-track {
    background: transparent;
}
#chat-container::-webkit-scrollbar-thumb {
    background: #374151;
    border-radius: 3px;
}

/* Animations */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: slideInUp 0.3s ease-out;
}
</style>

<script>
// Auto-resize textarea
function autoResize(textarea) {
    textarea.style.height = 'auto';
    textarea.style.height = Math.min(textarea.scrollHeight, 120) + 'px';
}

// Handle Enter key (Shift+Enter for new line)
function handleKeyPress(event) {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();
        document.getElementById('chat-form').dispatchEvent(new Event('submit'));
    }
}

// Quick ask buttons
function quickAsk(message) {
    document.getElementById('message-input').value = message;
    autoResize(document.getElementById('message-input'));
    document.getElementById('chat-form').dispatchEvent(new Event('submit'));
}

// Image preview
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('image-preview').classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Clear image
function clearImage() {
    document.getElementById('image-input').value = '';
    document.getElementById('image-preview').classList.add('hidden');
}

// Scroll to bottom on load
window.onload = function() {
    const container = document.getElementById('chat-container');
    container.scrollTop = container.scrollHeight;
}

// Fullscreen toggle
function toggleFullscreen() {
    const chatContainer = document.querySelector('.h-[calc(100vh-120px)]');
    const fullscreenIcon = document.getElementById('fullscreen-icon');
    const sidebar = document.querySelector('aside');
    const mainContent = document.querySelector('.lg\\:ml-64');
    
    if (document.fullscreenElement) {
        // Exit fullscreen
        document.exitFullscreen();
        fullscreenIcon.textContent = 'fullscreen';
        
        // Show sidebar
        if (sidebar) sidebar.style.display = '';
        if (mainContent) mainContent.style.marginLeft = '';
    } else {
        // Enter fullscreen
        chatContainer.requestFullscreen();
        fullscreenIcon.textContent = 'fullscreen_exit';
        
        // Optional: Hide sidebar in fullscreen
        // if (sidebar) sidebar.style.display = 'none';
        // if (mainContent) mainContent.style.marginLeft = '0';
    }
}

// Listen for fullscreen change
document.addEventListener('fullscreenchange', function() {
    const fullscreenIcon = document.getElementById('fullscreen-icon');
    if (!document.fullscreenElement) {
        fullscreenIcon.textContent = 'fullscreen';
    }
});

// Form submission with AJAX
document.getElementById('chat-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const messageInput = document.getElementById('message-input');
    const imageInput = document.getElementById('image-input');
    const sendBtn = document.getElementById('send-btn');
    const typingIndicator = document.getElementById('typing-indicator');
    const chatContainer = document.getElementById('chat-container');
    
    // Don't submit if empty
    if (!messageInput.value.trim() && !imageInput.files[0]) return;
    
    // Disable send button
    sendBtn.disabled = true;
    sendBtn.classList.add('opacity-50');
    
    // Show typing indicator
    typingIndicator.classList.remove('hidden');
    chatContainer.scrollTop = chatContainer.scrollHeight;
    
    // Create form data
    const formData = new FormData(this);
    
    // Add user message to chat immediately (visual feedback)
    const now = new Date();
    const timeString = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
    
    let userMessageHtml = '<div class="flex items-start gap-3 justify-end user-message animate-fade-in">';
    userMessageHtml += '<div class="max-w-[80%] order-1">';
    userMessageHtml += '<div class="bg-gradient-to-r from-primary-600 to-primary-700 rounded-2xl rounded-tr-sm p-4 shadow-lg shadow-primary-600/10">';
    
    if (imageInput.files[0]) {
        const imageUrl = URL.createObjectURL(imageInput.files[0]);
        userMessageHtml += '<div class="mb-2"><img src="' + imageUrl + '" class="max-w-[200px] max-h-[150px] rounded-lg object-cover"></div>';
    }
    
    if (messageInput.value.trim()) {
        userMessageHtml += '<div class="text-white text-sm leading-relaxed">' + messageInput.value.replace(/\n/g, '<br>') + '</div>';
    }
    
    userMessageHtml += '</div>';
    userMessageHtml += '<span class="text-xs text-gray-500 mr-2 mt-1 block text-right">' + timeString + '</span>';
    userMessageHtml += '</div>';
    userMessageHtml += '<div class="w-10 h-10 rounded-full bg-dark-700 flex items-center justify-center flex-shrink-0 order-2">';
    userMessageHtml += '<span class="material-symbols-outlined text-gray-400 text-sm">person</span>';
    userMessageHtml += '</div>';
    userMessageHtml += '</div>';
    
    chatContainer.insertAdjacentHTML('beforeend', userMessageHtml);
    chatContainer.scrollTop = chatContainer.scrollHeight;
    
    // Clear inputs
    const messageValue = messageInput.value;
    messageInput.value = '';
    messageInput.style.height = 'auto';
    clearImage();
    
    // Send AJAX request
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        // Hide typing indicator
        typingIndicator.classList.add('hidden');
        
        // Add AI response
        let aiResponseHtml = '<div class="flex items-start gap-3 ai-message animate-fade-in">';
        aiResponseHtml += '<div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-600 to-primary-700 flex items-center justify-center flex-shrink-0">';
        aiResponseHtml += '<span class="material-symbols-outlined text-white text-sm">smart_toy</span>';
        aiResponseHtml += '</div>';
        aiResponseHtml += '<div class="max-w-[80%]">';
        aiResponseHtml += '<div class="bg-dark-800 border border-dark-700 rounded-2xl rounded-tl-sm p-4 shadow-sm">';
        aiResponseHtml += '<div class="text-gray-200 text-sm leading-relaxed whitespace-pre-line">' + data.ai_response.response + '</div>';
        aiResponseHtml += '</div>';
        aiResponseHtml += '<span class="text-xs text-gray-500 ml-2 mt-1 block">' + data.ai_response.time + '</span>';
        aiResponseHtml += '</div>';
        aiResponseHtml += '</div>';
        
        chatContainer.insertAdjacentHTML('beforeend', aiResponseHtml);
        chatContainer.scrollTop = chatContainer.scrollHeight;
        
        // Re-enable send button
        sendBtn.disabled = false;
        sendBtn.classList.remove('opacity-50');
    })
    .catch(error => {
        console.error('Error:', error);
        typingIndicator.classList.add('hidden');
        sendBtn.disabled = false;
        sendBtn.classList.remove('opacity-50');
        
        // Show error message
        alert('Failed to send message. Please try again.');
    });
});
</script>
@endsection
