<!--begin::Form-->

<form id="form" enctype="multipart/form-data" method="POST" action="{{route('awards.update',$row->id)}}">
    @csrf
    @method('PUT')
    <div class="row g-4">


        <div class="form-group">
            <label for="image" class="form-control-label">{{helperTrans('admin.image')}} </label>
            <input type="file" class="dropify" name="image" data-default-file="{{get_file($row->image)}}" accept="image/*"/>
            <span
                class="form-text text-muted text-center">{{helperTrans('admin.Only the following formats are allowed: jpeg, jpg, png, gif, svg, webp, avif.')}}</span>
        </div>
        @foreach(languages() as $index=>$language)
           <div class="d-flex flex-column mb-7 fv-row col-sm-6">
               <!--begin::Label-->
               <label for="title{{$language->abbreviation}}" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                   <span class="required mr-1">{{helperTrans('admin.title')}}({{$language->abbreviation}})</span>
                   <span class="red-star">*</span>
               </label>
           
               <!--end::Label-->
               <input id="title{{$language->abbreviation}}" required type="text" class="form-control form-control-solid"
                      placeholder="" name="title[{{$language->abbreviation}}]" value="{{get_row_translations($row,'title',$language->abbreviation)}}"/>
           </div>
        @endforeach     
        
        
        @foreach(languages() as $index=>$language)

            <div class="d-flex flex-column mb-7 fv-row col-sm-12">
            <!--begin::Label-->
            <label for="desc{{$language->abbreviation}}" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">{{helperTrans('admin.Description')}} ({{$language->abbreviation}})</span>
                <span class="red-star">*</span>
            </label>

            <!--end::Label-->
            <textarea type="text" name="desc[{{$language->abbreviation}}]" value="" id="desc{{$language->abbreviation}}" required class="form-control form-control-solid"
                    placeholder="" >{{get_row_translations($row,'desc',$language->abbreviation)}}</textarea>
            </div>
        @endforeach  

        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
               <!--begin::Label-->
               <label for="points" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                   <span class="required mr-1">{{helperTrans('admin.points')}}</span>
                   <span class="red-star">*</span>
               </label>
           
               <!--end::Label-->
               <input id="points" required type="number" class="form-control form-control-solid"
                      placeholder="" name="points" value="{{$row->points}}"/>
        </div>

    </div>
</form>


<script>
    $('.dropify').dropify();
</script>
