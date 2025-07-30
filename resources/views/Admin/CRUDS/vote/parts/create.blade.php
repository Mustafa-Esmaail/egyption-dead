<!--begin::Form-->

<form id="form" enctype="multipart/form-data" method="POST" action="{{route('votes.store')}}">
    @csrf
    <div class="row g-4">


        
         @foreach(languages() as $index=>$language)
           
           <div class="d-flex flex-column mb-7 fv-row col-sm-6">
               <!--begin::Label-->
               <label for="title{{$language->abbreviation}}" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                   <span class="required mr-1">{{helperTrans('admin.title')}}({{$language->abbreviation}})</span>
                   <span class="red-star">*</span>
               </label>
           
               <!--end::Label-->
               <input id="title{{$language->abbreviation}}" required type="text" class="form-control form-control-solid"
                      placeholder="" name="title[{{$language->abbreviation}}]" value=""/>
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
                    placeholder="" ></textarea>
            </div>
        @endforeach


    </div>
</form>

<script>
    $('.dropify').dropify();
</script>
