@extends('Admin.layouts.inc.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Chat with {{ $user->first_name }} {{ $user->last_name }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.chat.index') }}" class="btn btn-default">
                                <i class="fas fa-arrow-left"></i> Back to Conversations
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chat-container" id="chatContainer">
                            @foreach ($messages as $message)
                                <div class="message {{ $message->is_admin ? 'admin-message' : 'user-message' }}">
                                    <div class="message-content">
                                        {{ $message->message }}
                                    </div>
                                    <div class="message-time">
                                        {{ $message->created_at->format('M d, Y H:i') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <form id="messageForm" class="mt-3">
                            @csrf
                            <div class="input-group">
                                <input type="text" id="messageInput" class="form-control"
                                    placeholder="Type your message...">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane"></i> Send
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .chat-container {
            height: 500px;
            overflow-y: auto;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 5px;
        }

        .message {
            margin-bottom: 15px;
        }

        .admin-message {
            margin-left: auto;
        }

        .user-message {
            margin-right: auto;
        }

        .message-content {
            padding: 10px 15px;
            border-radius: 15px;
            display: inline-block;
        }

        .admin-message .message-content {
            background: #007bff;
            color: white;
        }

        .user-message .message-content {
            background: #e9ecef;
            color: #212529;
        }

        .message-time {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }

        .admin-message .message-time {
            text-align: right;
        }
    </style>
@endsection

@section('js')
    <script>
        const userId = '{{ $user->id }}';
        const chatContainer = document.getElementById('chatContainer');
        const messageForm = document.getElementById('messageForm');
        const messageInput = document.getElementById('messageInput');

        function formatDate(date) {
            const d = new Date(date);
            const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            return `${months[d.getMonth()]} ${String(d.getDate()).padStart(2, '0')}, ${d.getFullYear()} ${String(d.getHours()).padStart(2, '0')}:${String(d.getMinutes()).padStart(2, '0')}`;
        }

        // Function to add a message to the chat
        function addMessage(message, isAdmin = true) {

            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${isAdmin ? 'admin-message' : 'user-message'}`;
            messageDiv.innerHTML = `
            <div class="message-content">${message}</div>
            <div class="message-time">${formatDate(new Date())}</div>
             `;
            chatContainer.appendChild(messageDiv);
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        // Function to check for new messages
        function checkNewMessages() {
            fetch(`{{ route('admin.chat.show', $user->id) }}`)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const messages = doc.querySelectorAll('.message');

                    // Get current message count
                    const currentCount = chatContainer.querySelectorAll('.message').length;

                    // If there are new messages, add them
                    if (messages.length > currentCount) {
                        for (let i = currentCount; i < messages.length; i++) {
                            const message = messages[i];
                            const isAdmin = message.classList.contains('admin-message');
                            const content = message.querySelector('.message-content').textContent;
                            addMessage(content, isAdmin);
                        }
                    }
                })
                .catch(error => console.error('Error checking messages:', error));
        }

        // Check for new messages every 5 seconds
        setInterval(checkNewMessages, 5000);

        // Send message
        messageForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const message = messageInput.value.trim();
            if (!message) {
                toastr.warning('Please enter a message');
                return;
            }

            fetch('{{ route('admin.chat.send') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json', // 👈 required to trigger wantsJson()
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        message: message
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('data', data)
                    if (data.success) {
                        messageInput.value = '';
                        toastr.success(data.message);
                        addMessage(message, true);
                    } else {
                        toastr.error(data.message || 'Error sending message');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('Error sending message');
                });
        });

        // Initial scroll to bottom
        chatContainer.scrollTop = chatContainer.scrollHeight;
    </script>
@endsection
