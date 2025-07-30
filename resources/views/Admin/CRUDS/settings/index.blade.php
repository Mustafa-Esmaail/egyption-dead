@extends('Admin.layouts.inc.app')
@section('title')
    @php

    $pageName = 'Team';
    $pageUrl = 'teams';
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
            <h5 class="card-title mb-0 flex-grow-1"> {{helperTrans('admin.General Settings')}} </h5>


            <form id="form" enctype="multipart/form-data" method="POST" action="{{route('settings.store')}}">
                @csrf
                <div class="row my-4 g-4">




                    <div class="d-flex flex-column mb-7 fv-row col-sm-6">
                        <label for="logo_header" class="form-control-label fs-6 fw-bold "> {{helperTrans('admin.Logo')}} </label>
                        <input type="file" class="dropify" name="logo_header"
                               data-default-file="{{get_file($settings->logo_header)}}"
                               accept="image/*"/>
                        <span class="form-text text-muted text-center">{{helperTrans('admin.Only the following formats are allowed: jpeg, jpg, png, gif, svg, webp, avif.')}}</span>
                    </div>


                    <div class="d-flex flex-column mb-7 fv-row col-sm-6">
                        <label for="fave_icon" class="form-control-label fs-6 fw-bold ">  {{helperTrans('admin.Icon')}}  </label>
                        <input type="file" id="fave_icon" class="dropify" name="fave_icon"
                               data-default-file="{{get_file($settings->fave_icon)}}"
                               accept="image/*"/>
                        <span class="form-text text-muted text-center">{{helperTrans('admin.Only the following formats are allowed: jpeg, jpg, png, gif, svg, webp, avif.')}}</span>
                    </div>



                    <div class="d-flex flex-column mb-7 fv-row col-sm-6">
                        <!--begin::Label-->
                        <label for="app_name" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                            <span class="required mr-1">  {{helperTrans('admin.App Name')}}</span>
                        </label>
                        <!--end::Label-->
                        <input id="app_name" type="text" class="form-control form-control-solid" name="app_name"
                               value="{{$settings->app_name}}"/>
                    </div>

                    <div class="d-flex flex-column mb-7 fv-row col-sm-6">
                        <!--begin::Label-->
                        <label for="facebook" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                            <span class="required mr-1">  {{helperTrans('admin.Facebook')}}</span>
                        </label>
                        <!--end::Label-->
                        <input id="facebook" type="text" class="form-control form-control-solid" name="facebook"
                               value="{{$settings->facebook}}"/>
                    </div>

                    <div class="d-flex flex-column mb-7 fv-row col-sm-6">
                        <!--begin::Label-->
                        <label for="twitter" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                            <span class="required mr-1">  {{helperTrans('admin.Twitter')}}</span>
                        </label>
                        <!--end::Label-->
                        <input id="twitter" type="text" class="form-control form-control-solid" name="twitter"
                               value="{{$settings->twitter}}"/>
                    </div>

                    <div class="d-flex flex-column mb-7 fv-row col-sm-6">
                        <!--begin::Label-->
                        <label for="youTube" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                            <span class="required mr-1">  {{helperTrans('admin.YouTube')}}</span>
                        </label>
                        <!--end::Label-->
                        <input id="youTube" type="text" class="form-control form-control-solid" name="youTube"
                               value="{{$settings->youTube}}"/>
                    </div>

                    <div class="d-flex flex-column mb-7 fv-row col-sm-6">
                        <!--begin::Label-->
                        <label for="instagram" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                            <span class="required mr-1">  {{helperTrans('admin.Instagram')}}</span>
                        </label>
                        <!--end::Label-->
                        <input id="instagram" type="text" class="form-control form-control-solid" name="instagram"
                               value="{{$settings->instagram}}"/>
                    </div>

                    <div class="d-flex flex-column mb-7 fv-row col-sm-6">
                        <!--begin::Label-->
                        <label for="tiktok" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                            <span class="required mr-1">  {{helperTrans('admin.Tiktok')}}</span>
                        </label>
                        <!--end::Label-->
                        <input id="tiktok" type="text" class="form-control form-control-solid" name="tiktok"
                               value="{{$settings->tiktok}}"/>
                    </div>
                    <div class="d-flex flex-column mb-7 fv-row col-sm-6">
                        <!--begin::Label-->
                        <label for="whatsApp" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                            <span class="required mr-1">  {{helperTrans('admin.whatsApp')}}</span>
                        </label>
                        <!--end::Label-->
                        <input id="whatsApp" type="text" class="form-control form-control-solid" name="whatsApp"
                               value="{{$settings->whatsApp}}"/>
                    </div>
                </div>

                <div class="row border rounded border-primary">
                        
                            <h5 class="required mr-1 mt-2 mb-2 text-primary">  {{helperTrans('admin.Points Settings')}}</h5>
             

                    <div class="d-flex flex-column mb-7 fv-row col-sm-6 mt-3">
                        <!--begin::Label-->
                        <label for="comment_point" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                            <span class="required mr-1">  {{helperTrans('admin.comment')}}</span>
                        </label>
                        <!--end::Label-->
                        <input id="comment_point" type="text" class="form-control form-control-solid" name="comment_points"
                               value="{{dynamicSetting('comment_points')}}"/>
                    </div>
                    
                    <div class="d-flex flex-column mb-7 fv-row col-sm-6 mt-3">
                        <!--begin::Label-->
                        <label for="like_points" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                            <span class="required mr-1">  {{helperTrans('admin.like')}}</span>
                        </label>
                        <!--end::Label-->
                        <input id="like_points" type="text" class="form-control form-control-solid" name="like_points"
                               value="{{dynamicSetting('like_points')}}"/>
                    </div>
                    
                    <div class="d-flex flex-column mb-7 fv-row col-sm-6 mt-3">
                        <!--begin::Label-->
                        <label for="signUp_points" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                            <span class="required mr-1">  {{helperTrans('admin.signUp')}}</span>
                        </label>
                        <!--end::Label-->
                        <input id="signUp_points" type="text" class="form-control form-control-solid" name="signUp_points"
                               value="{{dynamicSetting('signUp_points')}}"/>
                    </div>
                    
                    <div class="d-flex flex-column mb-7 fv-row col-sm-6 mt-3">
                        <!--begin::Label-->
                        <label for="vote_points" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                            <span class="required mr-1">  {{helperTrans('admin.vote')}}</span>
                        </label>
                        <!--end::Label-->
                        <input id="vote_points" type="text" class="form-control form-control-solid" name="vote_points"
                               value="{{dynamicSetting('vote_points')}}"/>
                    </div>
                    
                    <div class="d-flex flex-column mb-7 fv-row col-sm-6 mt-3">
                        <!--begin::Label-->
                        <label for="subscribe_points" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                            <span class="required mr-1">  {{helperTrans('admin.subscribe IN in Academy')}}</span>
                        </label>
                        <!--end::Label-->
                        <input id="vote_points" type="text" class="form-control form-control-solid" name="subscribe_points"
                               value="{{dynamicSetting('subscribe_points')}}"/>
                    </div>
                    
                    <div class="d-flex flex-column mb-7 fv-row col-sm-6 mt-3">
                        <!--begin::Label-->
                        <label for="add_plan" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                            <span class="required mr-1">  {{helperTrans('admin.add_plan')}}</span>
                        </label>
                        <!--end::Label-->
                        <input id="add_plan" type="text" class="form-control form-control-solid" name="add_plan"
                               value="{{dynamicSetting('add_plan')}}"/>
                    </div>
                    
                    <div class="d-flex flex-column mb-7 fv-row col-sm-6 mt-3">
                        <!--begin::Label-->
                        <label for="favourite_team" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                            <span class="required mr-1">  {{helperTrans('admin.Favourite Team')}}</span>
                        </label>
                        <!--end::Label-->
                        <input id="add_plan" type="text" class="form-control form-control-solid" name="favourite_team"
                               value="{{dynamicSetting('favourite_team')}}"/>
                    </div>
                    

                    <div class="d-flex flex-column mb-7 fv-row col-sm-6 mt-3">
                        <!--begin::Label-->
                        <label for="favourite_player" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                            <span class="required mr-1">  {{helperTrans('admin.Favourite Player')}}</span>
                        </label>
                        <!--end::Label-->
                        <input id="add_plan" type="text" class="form-control form-control-solid" name="favourite_player"
                               value="{{dynamicSetting('favourite_player')}}"/>
                    </div> 
                    
                    <div class="d-flex flex-column mb-7 fv-row col-sm-6 mt-3">
                        <!--begin::Label-->
                        <label for="upload_talant" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                            <span class="required mr-1">  {{helperTrans('admin.upload_talant')}}</span>
                        </label>
                        <!--end::Label-->
                        <input id="upload_talant" type="text" class="form-control form-control-solid" name="upload_talant"
                               value="{{dynamicSetting('upload_talant')}}"/>
                    </div>
                    
                    <div class="d-flex flex-column mb-7 fv-row col-sm-6 mt-3">
                        <!--begin::Label-->
                        <label for="add_rate" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                            <span class="required mr-1">  {{helperTrans('admin.add_rate')}}</span>
                        </label>
                        <!--end::Label-->
                        <input id="add_rate" type="text" class="form-control form-control-solid" name="add_rate"
                               value="{{dynamicSetting('add_rate')}}"/>
                    </div>
                    
                    <div class="d-flex flex-column mb-7 fv-row col-sm-6 mt-3">
                        <!--begin::Label-->
                        <label for="upload_product" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                            <span class="required mr-1">  {{helperTrans('admin.upload_product')}}</span>
                        </label>
                        <!--end::Label-->
                        <input id="upload_product" type="text" class="form-control form-control-solid" name="upload_product"
                               value="{{dynamicSetting('upload_product')}}"/>
                    </div> 
                    
                    <div class="d-flex flex-column mb-7 fv-row col-sm-6 mt-3">
                        <!--begin::Label-->
                        <label for="var" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                            <span class="required mr-1">  {{helperTrans('admin.var')}}</span>
                        </label>
                        <!--end::Label-->
                        <input id="vote" type="text" class="form-control form-control-solid" name="var_points"
                               value="{{dynamicSetting('var_points')}}"/>
                    </div>
                    
                

                </div>

                 

                    <div class="col-sm-12 pb-3 p-2">
                        <label for="address" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                            <span class="required mr-1">  {{helperTrans('admin.Privacy_and_Policy')}}       <span class="red-star">*</span></span>
                        </label>
                        <textarea name="privacy_police" id="address" class="form-control " rows="5"
                                  placeholder="">{{$settings->privacy_police}}</textarea>
                    </div>



                    @if(auth('admin')->user()->can('edit settings'))

                    <div class="my-4">
                        <button type="submit" class="btn btn-success"> {{helperTrans('admin.Update')}}</button>
                    </div>
                    @endif


                </div>
            </form>

        </div>
    </div>

@endsection

@section('js')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>


    <script>
        $('.dropify').dropify();

    </script>


    <script>
        // CKEDITOR.replace('privacy');


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
