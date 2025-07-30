@extends('Admin.layouts.inc.app')
@section('title')
    @php

    $pageName = 'Admins';
    $pageUrl = 'admins';
    $tdList = ['name','email','is_active','image','created_at','action']; 

    @endphp
    {{helperTrans("admin.$pageName")}}
@endsection
@section('css')

@endsection
@section('toolbar')

@include('Admin.layouts.inc.toolbar')

@endsection

@section('content')

    @include('Admin.layouts.inc.tableHeader',['pageName'=>$pageName,'tdList'=>$tdList])
    @include('Admin.layouts.inc.modal',['pageName'=>$pageName])

@endsection
@section('js')

    @include('Admin.layouts.inc.ajax',['url'=>$pageUrl])

    <script src="{{url('assets/dashboard/js/select2.js')}}"></script>
    <script>
        $(document).on('change','#check_all',function () {
            var checked = $(this).is(':checked');

            if (checked){
                $('.checkbox').prop('checked', true);
            }
            else {
                $('.checkbox').prop('checked', false);
            }
        })
    </script>


<script>
        $(document).on('change', '.activeBtn', function () {
            var id = $(this).attr('data-id');

            $.ajax({
                type: 'GET',
                url: "{{route('admin.active.admin')}}",
                data: {
                    id: id,
                },

                success: function (res) {
                    if (res['status'] == true) {

                        toastr.success("{{helperTrans('admin.operation accomplished successfully')}}")
                        // $('#table').DataTable().ajax.reload(null, false);
                    } else {
                        // location.reload();

                    }
                },
                error: function (data) {
                    // location.reload();
                }
            });


        })
    </script>

@endsection
