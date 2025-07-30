@extends('Admin.layouts.inc.app')
@section('title')
    @php

    $pageName = 'team Groups';
    $pageUrl = 'team_groups';
    $tdList = ['title','status','image','action']; 

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


        $(document).on('change', '.change-status', function () {
            var status = $(this).val();
            var id = $(this).data('id');

            $.ajax({
                url: "{{route('admin.updateTeamGroup')}}",
                method: 'POST',
                data: {
                    id: id,
                    status: status,
                    _token: $('meta[name="csrf-token"]').attr('content') // CSRF token
                },
                success: function (response) {
                    if (response.success) {

                        let table = $('#table').DataTable();
                        table.ajax.url("{{ route('team_groups.index') }}").load(); 
                        toastr.success(response.message)
                        // alert('Status updated successfully!');
                    } else {
                        alert('Failed to update status.');
                    }
                },
                error: function (error) {
                    alert('error');
                    console.log(error);
                   
                }
            });
        });
    </script>
@endsection
