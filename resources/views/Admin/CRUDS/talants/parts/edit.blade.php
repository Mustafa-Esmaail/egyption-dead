<!--begin::Form-->

<form id="form" enctype="multipart/form-data" method="POST" action="{{route('talants.update',$data['talant']->id)}}">
    @csrf
    @method('PUT')
    <div class="row g-4">


        @foreach(languages() as $index=>$language)

        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <!--begin::Label-->
            <label for="title{{$language->abbreviation}}" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class=" mr-1">{{helperTrans('admin.title')}}({{$language->abbreviation}})</span>
                {{-- <span class="red-star">*</span> --}}
            </label>

            <!--end::Label-->
            <input id="title{{$language->abbreviation}}"  type="text" class="form-control form-control-solid"
                    placeholder="" name="title[{{$language->abbreviation}}]" value="{{get_row_translations($data['talant'],'title',$language->abbreviation)}}"/>
        </div>
        @endforeach

        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
               <!--begin::Label-->
               <label for="age" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                   <span class="required mr-1">{{helperTrans('admin.phone')}}</span>
                   <span class="red-star">*</span>
               </label>

               <!--end::Label-->
               <input type="text" name="phone" value="{{$data['talant']->phone}}" id="phone" required class="form-control form-control-solid"
                      placeholder=""  />
        </div>



        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
               <!--begin::Label-->
               <label for="age" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                   <span class="required mr-1">{{helperTrans('admin.age')}}</span>
                   <span class="red-star">*</span>
               </label>

               <!--end::Label-->
               <input type="number" name="age" value="{{ $data['talant']->age }}" id="age" required class="form-control form-control-solid"
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
                <option @if($data['talant']->user_id == $user->id) selected @endif value="{{ $user->id }}">{{ $user->first_name .' '.$user->last_name }}</option>
                @endforeach
            </select>
        </div>


        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
        <label for="" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
            <span class="required mr-1">{{helperTrans('admin.Country')}}</span>
            <span class="red-star">*</span>
        </label>
        <select name="country_id"  id="country-select"  class="form-control form-control-solid">
            <option value=""></option>
            @foreach($data['countries'] as $country)
            <option @if($data['talant']->country_id == $country->id) selected @endif value="{{ $country->id }}">{{ $country->title }}</option>
            @endforeach
        </select>
        </div>


        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <label for="" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">{{helperTrans('admin.city')}}</span>
                <span class="red-star">*</span>
            </label>
            <select name="city_id" id="city-select"  class="form-control form-control-solid">
                <option value=""></option>
                @foreach($data['cities'] as $city)
                <option @if($data['talant']->city_id == $city->id) selected @endif value="{{ $city->id }}">{{ $city->title }}</option>
                @endforeach
            </select>
        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-12">
               <!--begin::Label-->
               <label for="age" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                   <span class="required mr-1">{{helperTrans('admin.address')}}</span>
                   {{-- <span class="red-star">*</span> --}}
               </label>

               <!--end::Label-->
               <input type="text" name="address" value="{{ $data['talant']->address }}" id="address"  class="form-control form-control-solid"
                      placeholder=""  />
        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-12">
               <!--begin::Label-->
               <label for="video" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                   <span class="required mr-1">{{helperTrans('admin.video')}}</span>
                   <span class="red-star">*</span>
               </label>

               <!--end::Label-->
               <input type="file" accept="video/*" name="video" value="" id="video" class="form-control form-control-solid"/>
        </div>

    </div>
</form>


<script>
    $('.dropify').dropify();
</script>
