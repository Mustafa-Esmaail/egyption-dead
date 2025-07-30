@extends('Admin.layouts.inc.app')
@section('title')
    @php

    $pageName = 'Award';
    $pageUrl = 'awards';
    $tdList = ['title','desc','points','image','action']; 

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
    </script>

@endsection
