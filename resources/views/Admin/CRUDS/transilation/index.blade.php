@extends('Admin.layouts.inc.app')
@section('title')
    @php

    $pageName = 'Translation';
    $pageUrl = 'Translation';
    $tdList = ['title','players','image','action']; 

    @endphp
    {{helperTrans("admin.$pageName")}}
@endsection

@section('css')

@endsection

@section('toolbar')

    @include('Admin.layouts.inc.toolbar')

@endsection

@section('content')

    <div class="card">
        <div class="card-header ">

                    @php
                        $locale = $data['lang'] == 'en' ? 'ar' : 'en';
                        $lang = $data['lang'] == 'en' ? 'Arabic' : 'English';
                    @endphp
            <h5 class="card-title mb-0 flex-grow-1"> {{helperTrans('admin.General Translation')}} </h5>
                <div class="card-toolbar">
                    <a href="{{route('translation.index')."?transLang=$locale"}}"   class="btn btn-sm btn-light-primary">
                            <i class="fas fa-language mr-2"></i>
                            {{helperTrans("admin.$lang")}}
                    </a>
                </div>
            </div>  

            <form id="" enctype="multipart/form-data" method="POST" action="{{ route('translation.index') }}"> 
                @csrf
                <div class="row ps-5 border-0">
                    <div class="form-group col-3">
                        <input class="form-control" type="text" name="searchValue" value="" placeholder="search by key or value">
                    </div>
                    
                    <div class="form-group col-3">
                        <button class="btn btn-primary">
                            <i class="text-light fas fa-search mr-2"></i>
                        </button>
                    </div>
                </div>
            </form>
            
            <form id="form" enctype="multipart/form-data" method="POST" action="{{route('translation.update')}}">
                @csrf
                <div class="card-body py-3">

                    <input type="hidden" name="transLang" value="{{$data['lang']}}">
                    <!--begin::Table container-->
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table id="table" class="table align-middle gs-0 gy-4 table table-bordered dt-responsive nowrap table-striped align-middle">
                            <!--begin::Table head-->
                            <thead>
                                    <tr>
                                        <th>#</th>
                                        <th> <h6> {{ helperTrans('Key') }} </h6></th>
                                        <th> <h6> {{ helperTrans('Value') }} </h6></th>
                                    </tr>
                            </thead>
                            <tbody>

                            <?php $idex = 1 ?>
                            @foreach ($data['translations'] as $key => $value)
                                <tr  class="rw">
                                    <td class="" data-label="Serial">{{ $idex++ }}</td>
                                    
                                    <td class="" style="width:35% !important" class="text-start"> <h6>{{ str_replace('_', ' ', $key) }}</h6></td>
                                       
                                    <td colspan="8"   data-label="Value">
                                        <input type="text" class="form-control value" 
                                            name="values[{{ $key }}]"
                                            value="{{ $value }}">
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                        <!--end::Table-->
                        <
                    </div>
                    <!--end::Table container-->
                
                    {!! $data['translations']->links('pagination::bootstrap-4') !!}
                            <div class="my-4">
                                <button type="submit" class="btn btn-success"> {{helperTrans('admin.Update')}}</button>
                            </div>
                        
                </div>
            </form>

        
    </div>

@endsection

@section('js')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>


    <script>
        $('.dropify').dropify();

    </script>

    <script>
        $(document).on('submit', "form#form", function (e) {
            e.preventDefault();

            var formData = new FormData(this);

            var url = $('#form').attr('action');
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,

                complete: function () {
                },
                success: function (data) {

                    window.setTimeout(function () {

                        // $('#product-model').modal('hide')
                        if (data.code == 200) {
                            toastr.success(data.message)
                        } else {
                            toastr.error(data.message)
                        }
                    }, 1000);


                },
                error: function (data) {
                    if (data.status === 500) {
                        toastr.error('{{helperTrans('admin.there is an error')}}')
                    }

                    if (data.status === 422) {
                        var errors = $.parseJSON(data.responseText);

                        $.each(errors, function (key, value) {
                            if ($.isPlainObject(value)) {
                                $.each(value, function (key, value) {
                                    toastr.error(value)
                                });

                            } else {

                            }
                        });
                    }
                    if (data.status == 421) {
                        toastr.error(data.message)
                    }

                },//end error method

                cache: false,
                contentType: false,
                processData: false
            });
        });
    </script>

@endsection
