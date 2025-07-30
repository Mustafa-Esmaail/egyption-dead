<!--begin::Form-->

<form id="form" enctype="multipart/form-data" method="POST" action="{{route('vars.store')}}">
    @csrf
    <div class="row g-4">


        <div class="d-flex flex-column mb-7 fv-row col-sm-12">
            <div class="form-group">
                <label for="image" class="form-control-label">{{helperTrans('admin.image')}} </label>
                <input type="file" class="dropify" name="image" data-default-file="{{get_file()}}" accept="image/*"/>
                <span
                    class="form-text text-muted text-center">{{helperTrans('admin.Only the following formats are allowed: jpeg, jpg, png, gif, svg, webp, avif.')}}</span>
            </div>
        </div>

       

        <div class="d-flex flex-column mb-7 fv-row col-sm-12">
               <!--begin::Label-->
               <label for="desc" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                   <span class="require mr-1">{{helperTrans('admin.video')}}</span>
                  
               </label>
               <!--end::Label-->
               <input id="video"  type="text" class="form-control form-control-solid"
                      placeholder="" name="video" value=""/> </textarea>
        </div>
    

         @foreach(languages() as $index=>$language)
           
           <div class="d-flex flex-column mb-7 fv-row col-sm-12">
               <!--begin::Label-->
               <label for="desc{{$language->abbreviation}}" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                   <span class="required mr-1">{{helperTrans('admin.description')}}({{$language->abbreviation}})</span>
                   <span class="red-star">*</span>
               </label>
               <!--end::Label-->
               <textarea name="desc[{{ $language->abbreviation }}]" id="{{'desc_'.$language->abbreviation }}" class="form-control" rows="5" placeholder=""></textarea>
            
           </div>
           
        @endforeach     


    </div>
</form>

<script>
    $('.dropify').dropify();
</script>
