<!--begin::Form-->

<form id="form" enctype="multipart/form-data" method="POST" action="{{route('talants.store')}}">
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

        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
               <!--begin::Label-->
               <label for="age" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                   <span class="required mr-1">{{helperTrans('admin.phone')}}</span>
                   <span class="red-star">*</span>
               </label>
           
               <!--end::Label-->
               <input type="text" name="phone" value="" id="phone" required class="form-control form-control-solid"
                      placeholder=""  />
        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
               <!--begin::Label-->
               <label for="age" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                   <span class="required mr-1">{{helperTrans('admin.address')}}</span>
                   <span class="red-star">*</span>
               </label>
           
               <!--end::Label-->
               <input type="text" name="address" value="" id="address" required class="form-control form-control-solid"
                      placeholder=""  />
        </div>


        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
               <!--begin::Label-->
               <label for="age" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                   <span class="required mr-1">{{helperTrans('admin.age')}}</span>
                   <span class="red-star">*</span>
               </label>
           
               <!--end::Label-->
               <input type="number" name="age" value="" id="age" required class="form-control form-control-solid"
                      placeholder=""  />
        </div>

        
        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <label for="" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">{{helperTrans('admin.User')}}</span>
                <span class="red-star">*</span>
            </label>
            <select name="user_id" class="form-control form-control-solid">
                <option value=""></option>
                @foreach($data['users'] as $user)
                <option value="{{ $user->id }}">{{ $user->first_name .' '.$user->last_name }}</option>
                @endforeach
            </select>
        </div> 

        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
    <label for="" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
        <span class="required mr-1">{{ helperTrans('admin.Country') }}</span>
        <span class="red-star">*</span>
    </label>
    <select name="country_id" id="country-select" class="form-control form-control-solid">
        <option value="">Select a country</option>
        @foreach($data['countries'] as $country)
        <option value="{{ $country->id }}">{{ $country->title }}</option>
        @endforeach
    </select>
</div>

<div class="d-flex flex-column mb-7 fv-row col-sm-6">
    <label for="" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
        <span class="required mr-1">{{ helperTrans('admin.city') }}</span>
        <span class="red-star">*</span>
    </label>
    <select name="city_id" id="city-select" class="form-control form-control-solid">
        <option value="">Select a city</option>
        <!-- Cities will be loaded dynamically -->
    </select>
</div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-12">
               <!--begin::Label-->
               <label for="age" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                   <span class="required mr-1">{{helperTrans('admin.video')}}</span>
                   <span class="red-star">*</span>
               </label>
           
               <!--end::Label-->
               <input type="file" accept="video/*" name="video" value="" id="video" class="form-control form-control-solid"
                        />
        </div>

    </div>
</form>

<script>
    $('.dropify').dropify();
</script>

