@extends('Admin.layouts.inc.app')
@section('title')
    @php

        $pageName = 'talants';
        $pageUrl = 'talants';

        $tdList = [
            'title'
            ,'username'
            ,'email'
            ,'status'
            ,'is_featured'
            ,'phone'
            ,'country'
            ,'address'
            ,'age'
            ,'recommended'
            ,'video'
            ,'country'
            ,'city'
            ,'user'
            ,'created_at'
            ,'action'
        ];

    @endphp

    {{helperTrans("admin.$pageName")}}
@endsection
@section('css')

@endsection
@section('toolbar')

@include('Admin.layouts.inc.toolbar')

@endsection

@section('content')

    @include('Admin.layouts.inc.tableHeader',['pageName'=>$pageName])
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
    </script>
    <script>
$(document).on('change', '.change-status', function () {
    var status = $(this).val();
    var id = $(this).data('id');

    $.ajax({
        url: "{{route('admin.updateTalantStatus')}}",
        method: 'POST',
        data: {
            id: id,
            status: status,
            _token: $('meta[name="csrf-token"]').attr('content') // CSRF token
        },
        success: function (response) {
            if (response.success) {

                let table = $('#table').DataTable();
                table.ajax.url("{{ route('talants.index') }}").load();
                toastr.success(response.message)
                // alert('Status updated successfully!');
            } else {
                alert('Failed to update status.');
            }
        },
        error: function () {
            alert('An error occurred.');
        }
    });
});
</script>

<script>
$(document).on('change', '.toggle-featured', function () {
    var id = $(this).data('id');
    var isChecked = $(this).is(':checked');

    $.ajax({
        url: "{{route('admin.talants.toggle-featured')}}",
        method: 'POST',
        data: {
            id: id,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            if (response.success) {
                let table = $('#table').DataTable();
                table.ajax.url("{{ route('talants.index') }}").load();
                toastr.success(response.message);
            } else {
                toastr.error('Failed to update featured status.');
            }
        },
        error: function () {
            toastr.error('An error occurred.');
        }
    });
});
</script>

@endsection
