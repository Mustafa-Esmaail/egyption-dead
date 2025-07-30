@extends('Admin.layouts.inc.app')
@php

    $pageName = 'award-redeem-requests';
    $pageUrl = 'award-redeem-requests';
    $tdList = ['user', 'award', 'user_points', 'status', 'created_at', 'action'];

@endphp
@section('title')
    {{ helperTrans('admin.award_redeem_requests') }}
@endsection
@section('toolbar')
    @include('Admin.layouts.inc.toolbar')
@endsection

@section('content')
    @include('Admin.layouts.inc.tableHeader', ['pageName' => $pageName, 'tdList' => $tdList])
    @include('Admin.layouts.inc.modal', ['pageName' => $pageName])
@endsection

@section('js')
    @include('Admin.layouts.inc.ajax', ['url' => $pageUrl])

    <script>
        $(function() {

            // Delete button click handler
            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: "{{ helperTrans('admin.are_you_sure') }}",
                    text: "{{ helperTrans('admin.you_wont_be_able_to_revert_this') }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{ helperTrans('admin.yes_delete_it') }}"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('award-redeem-requests.destroy', '') }}/" + id,
                            type: 'DELETE',
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire(
                                        "{{ helperTrans('admin.deleted') }}",
                                        "{{ helperTrans('admin.your_file_has_been_deleted') }}",
                                        'success'
                                    );
                                    $('#award-redeem-requests-table').DataTable().ajax
                                        .reload();
                                }
                            }
                        });
                    }
                });
            });

            // Status toggle click handler
            // Status select change handler
            $(document).on('change', '.status-select', function() {
                var id = $(this).data('id');
                var newStatus = $(this).val();

                $.ajax({
                    url: "{{ route('admin.award-redeem-requests.update-status') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        status: newStatus
                    },
                    success: function(response) {
                        toastr.success(
                            "{{ helperTrans('admin.operation accomplished successfully') }}"
                            );

                        if (response.success) {
                            $('#award-redeem-requests-table').DataTable().ajax.reload();
                        }
                    },
                    error: function() {
                        toastr.error("{{ helperTrans('admin.something went wrong') }}");
                    }
                });
            });

        });
    </script>
@endsection
