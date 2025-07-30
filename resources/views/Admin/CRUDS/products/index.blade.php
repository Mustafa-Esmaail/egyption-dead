@extends('Admin.layouts.inc.app')
@section('title')
    @php

        $pageName = 'products';
        $pageUrl = 'products';

        $tdList = ['title', 'username', 'email', 'status', 'desc', 'price', 'category', 'image', 'action'];

    @endphp

    {{ helperTrans("admin.$pageName") }}
@endsection
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css" rel="stylesheet">
    <style>
        .stat-card {
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .recent-activity {
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
@endsection
@section('toolbar')
    @include('Admin.layouts.inc.toolbar')
@endsection


@section('content')
<div class="row g-5 g-xl-8 mb-5">

    <!-- Products Card -->
    <div class="col-xl-3">

            <div class="card card-xl-stretch mb-xl-8 stat-card">
                <div class="card-body p-0">
                    <div class="d-flex flex-stack card-p flex-grow-1">
                        <span class="symbol symbol-50px me-2">
                            <span class="symbol-label bg-light-warning">
                                <i class="mdi mdi-check-circle text-success fs-2x"></i>
                            </span>
                        </span>
                        <div class="d-flex flex-column text-end">
                            <span class="text-dark fw-bolder fs-2">{{ $totalApprovedProducts ?? 0 }}</span>
                            <span class="text-muted fw-bold mt-1">{{ helperTrans('admin.Total Approved Products') }}</span>
                        </div>
                    </div>
                </div>
            </div>
    </div>
     <!-- Products Card -->
     <div class="col-xl-3">

            <div class="card card-xl-stretch mb-xl-8 stat-card">
                <div class="card-body p-0">
                    <div class="d-flex flex-stack card-p flex-grow-1">
                        <span class="symbol symbol-50px me-2">
                            <span class="symbol-label bg-light-warning">
                                <i class="mdi mdi-clock-outline text-warning fs-2x"></i>
                            </span>
                        </span>
                        <div class="d-flex flex-column text-end">
                            <span class="text-dark fw-bolder fs-2">{{ $totalPendingProducts ?? 0 }}</span>
                            <span class="text-muted fw-bold mt-1">{{ helperTrans('admin.Total Pending Products') }}</span>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>

    @include('Admin.layouts.inc.tableHeader', ['pageName' => $pageName])
    @include('Admin.layouts.inc.modal', ['pageName' => $pageName, 'modalWidth' => 'modal-xl'])
@endsection
@section('js')
    @include('Admin.layouts.inc.ajax', ['url' => $pageUrl])

    <script>
        $(document).on('change', '#check_all', function() {
            var checked = $(this).is(':checked');

            if (checked) {
                $('.checkbox').prop('checked', true);
            } else {
                $('.checkbox').prop('checked', false);
            }
        })
    </script>


    <script>
        $(document).on('change', '.change-status', function() {
            var status = $(this).val();
            var id = $(this).data('id');

            $.ajax({
                url: "{{ route('admin.updateProductStatus') }}",
                method: 'POST',
                data: {
                    id: id,
                    status: status,
                    _token: $('meta[name="csrf-token"]').attr('content') // CSRF token
                },
                success: function(response) {
                    if (response.success) {

                        let table = $('#table').DataTable();
                        table.ajax.url("{{ route('products.index') }}").load();
                        toastr.success(response.message)
                        // alert('Status updated successfully!');
                    } else {
                        toastr.error('Failed to update status.')
                        // alert('Failed to update status.');
                    }
                },
                error: function() {
                    alert('An error occurred.');
                }
            });
        });
    </script>
@endsection
