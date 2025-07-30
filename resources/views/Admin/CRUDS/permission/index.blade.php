@extends('Admin.layouts.inc.app')

@section('title')
    {{helperTrans('admin.Permissions')}}
@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="d-flex flex-column-fluid">
            <div class="container-fluid">
                <div class="card card-custom">
                    <div class="card-header flex-wrap py-5">
                        <div class="card-title">
                            <h3 class="card-label">{{helperTrans('admin.Permissions')}}
                            </h3>
                        </div>
                        @if(auth()->guard('admin')->user()->can('add permission'))
                            <div class="card-toolbar">
                                <a href="{{route('admin.permissions.create')}}"
                                   class="btn btn-primary font-weight-bolder">
                                    <span class="svg-icon svg-icon-md">
                                        <i class="fas fa-plus"></i>
                                    </span>
                                    {{helperTrans('admin.Add New')}}
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover table-checkable" id="kt_datatable"
                               style="margin-top: 13px !important">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>{{helperTrans('admin.Name')}}</th>
                                <th>{{helperTrans('admin.Guard Name')}}</th>
                                <th>{{helperTrans('admin.Created At')}}</th>
                                <th>{{helperTrans('admin.Actions')}}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            var table = $('#kt_datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.permissions.index') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'guard_name', name: 'guard_name'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                order: [[0, 'desc']],
            });

            $(document).on('click', '.delete', function () {
                var id = $(this).data('id');
                Swal.fire({
                    title: "{{helperTrans('admin.Are you sure?')}}",
                    text: "{{helperTrans('admin.You will not be able to recover this permission!')}}",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "{{helperTrans('admin.Yes, delete it!')}}",
                    cancelButtonText: "{{helperTrans('admin.No, cancel!')}}",
                    reverseButtons: true
                }).then(function (result) {
                    if (result.value) {
                        $.ajax({
                            url: "{{ route('admin.permissions.destroy', '') }}/" + id,
                            type: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function (response) {
                                if (response.success) {
                                    toastr.success(response.message);
                                    table.ajax.reload();
                                } else {
                                    toastr.error(response.message);
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
