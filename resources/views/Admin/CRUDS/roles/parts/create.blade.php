<!--begin::Form-->

<form id="form" enctype="multipart/form-data" method="POST" action="{{route('roles.store')}}">
    @csrf
    <div class="row g-4">





        <div class="d-flex flex-column mb-7 fv-row col-sm-12">
            <!--begin::Label-->
            <label for="name" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> {{helperTrans('admin.Name')}} <span class="red-star">*</span></span>
            </label>
            <!--end::Label-->
            <input required id="name" type="text" class="form-control form-control-solid" placeholder="" name="name" value=""/>
        </div>


        <div class="d-flex justify-content-center mt-3">

            <div class="col-md-4 p-1">
                    <span class="form-check form-switch  " @if( app()->getLocale()=='en') style="border:1px solid #F3F3F9;padding: 10px; padding-left: 50px;border-radius: 4px;" @else  style="border:1px solid #F3F3F9;padding: 10px; padding-right: 40px;border-radius: 4px;" @endif>
                      <input class="form-check-input  " type="checkbox" name="check_all" value=""
                             id="check_all">
                      <label class="form-check-label mx-1" for="check_all">
                       {{helperTrans('admin.Check All')}}
                      </label>
                    </span>
            </div>

        </div>




        <div class=" row my-4" id="permission_data">

            @foreach($permission as $row)
                <div class="col-md-3 p-1">
                    <span class="form-check form-switch   " @if( app()->getLocale()=='en') style="border:1px solid #F3F3F9;padding: 10px; padding-left: 50px;border-radius: 4px;" @else  style="border:1px solid #F3F3F9;padding: 10px; padding-right: 40px;border-radius: 4px;" @endif>
                      <input  class="form-check-input  checkbox" type="checkbox" name="permission[]" value="{{$row->id}}"
                              id="flexCheckDefault{{$row->id}}">
                      <label class="form-check-label " for="flexCheckDefault{{$row->id}}">
                      {{$row->name}}
                      </label>
                    </span>
                </div>
            @endforeach
        </div>


        <input type="hidden" name="guard_name" value="admin">



    </div>
</form>

<script>
    $('.dropify').dropify();
</script>
