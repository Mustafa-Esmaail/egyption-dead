@extends('Admin.layouts.inc.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Send Message</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.chat.index') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Back to Conversations
                        </a>
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.chat.send') }}" method="POST" id="messageForm">
                        @csrf
                        <div class="form-group">
                            <label for="user_id">Select User</label>
                            <select name="user_id" id="user_id" class="form-control select2">
                                <option value="all">All Users</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label for="message">Message</label>
                            <textarea name="message" id="message" rows="4" class="form-control" required></textarea>
                        </div>
                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Message Sent Successfully</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>What would you like to do next?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Send Another Message</button>
                <a href="{{ route('admin.chat.index') }}" class="btn btn-primary">Go to Conversations</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: 38px;
        border: 1px solid #ced4da;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select a user",
            allowClear: true
        });

        $('#messageForm').on('submit', function(e) {
            e.preventDefault();
            const userId = $('#user_id').val();
            const message = $('#message').val();

            if (!message) {
                toastr.warning('Please enter a message');
                return;
            }

            if (!userId) {
                toastr.warning('Please select a user');
                return;
            }

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    user_id: userId,
                    message: message
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#message').val('');

                        if (userId === 'all') {
                            // For "All Users", show the success modal
                            $('#successModal').modal('show');
                        } else {
                            // For single user, redirect to chat with that user
                            setTimeout(function() {
                                window.location.href = '{{ route("admin.chat.index") }}';
                            }, 1500);
                        }
                    } else {
                        toastr.error(response.message || 'Error sending message');
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    toastr.error(response?.message || 'Error sending message');
                }
            });
        });

        // Handle "Send Another Message" button
        $('#successModal .btn-secondary').on('click', function() {
            $('#successModal').modal('hide');
            $('#message').val('').focus();
        });
    });
</script>
@endpush
