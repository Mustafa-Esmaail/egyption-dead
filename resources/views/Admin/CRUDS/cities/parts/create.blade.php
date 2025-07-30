<!--begin::Form-->

<form id="form" enctype="multipart/form-data" method="POST" action="{{route('cities.store')}}">
    @csrf
    <div class="row g-4">

        
     
      @foreach(languages() as $index=>$language)
           
           <div class="d-flex flex-column mb-7 fv-row col-sm-6">
               <!--begin::Label-->
               <label for="title_{{$language->abbreviation}}" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                   <span class="required mr-1">{{helperTrans('admin.Name')}}({{$language->abbreviation}})</span>
                   <span class="red-star">*</span>
               </label>
           
               <!--end::Label-->
               <input id="title_{{$language->abbreviation}}" required type="text" class="form-control form-control-solid"
                      placeholder="" name="title[{{$language->abbreviation}}]" value=""/>
           </div>
           
        @endforeach     
        <!--begin::Input group-->

        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <label for="" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">{{helperTrans('admin.Country')}}</span>
                <span class="red-star">*</span>
            </label>
            <select name="country_id" class="form-control form-control-solid">
                <option value=""></option>
                @foreach($data['countries'] as $country)
                <option value="{{ $country->id }}">{{ $country->title }}</option>
                @endforeach
            </select>
        </div> 
      

        




    </div>
</form>
