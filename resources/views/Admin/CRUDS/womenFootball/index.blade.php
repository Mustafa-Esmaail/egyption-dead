@extends('Admin.layouts.inc.app')
@section('title')
    @php

        $pageName = 'women football';
        $pageUrl = 'women-football';

        $tdList = [
            'title'
            ,'video'
            ,'image'
            ,'category'
            ,'short_desc'
            ,'desc'
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
    @include('Admin.layouts.inc.modal',['pageName'=>$pageName,'modalWidth' => 'modal-xl'])

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
