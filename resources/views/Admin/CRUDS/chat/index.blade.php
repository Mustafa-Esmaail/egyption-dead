@extends('Admin.layouts.inc.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 align-items-center">
        <div class="col-md-8">
            <form action="{{ route('admin.chat.index') }}" method="GET" class="input-group input-group-sm">
                <input type="text" name="search" class="form-control" placeholder="Search users..." value="{{ request('search') }}">
                <div class="input-group-append">
                    <button type="submit" class=" ml-2 btn btn-primary btn-sm">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="col-md-4 text-md-right mt-2 mt-md-0">
            <a href="{{ route('admin.chat.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> New Message
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chat Conversations</h3>


                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Last Message</th>
                                    <th>Unread Messages</th>
                                    <th>Last Activity</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($conversations as $conversation)
                                <tr>
                                    <td>{{ $conversation->user->first_name }} {{ $conversation->user->last_name }}</td>
                                    <td>{{ Str::limit($conversation->last_message, 50) }}</td>
                                    <td>
                                        <span class="badge badge-danger">{{ $conversation->unread_count }}</span>
                                    </td>
                                    <td>{{ $conversation->last_activity ? $conversation->last_activity->diffForHumans() : 'N/A'  }}</td>
                                    <td>
                                        <a href="{{ route('admin.chat.show', $conversation->user_id) }}"
                                           class="btn btn-primary btn-sm">
                                            <i class="fas fa-comments"></i> Chat
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .chat-list-item {
        cursor: pointer;
        transition: background-color 0.3s;
    }
    .chat-list-item:hover {
        background-color: #f8f9fa;
    }
    .unread-badge {
        background-color: #dc3545;
        color: white;
        padding: 2px 6px;
        border-radius: 10px;
        font-size: 12px;
    }
</style>
@endpush
