<!--begin::Form-->

<form id="form" enctype="multipart/form-data" method="POST" action="{{route('players.update',$data['player']->id)}}">
    @csrf
    @method('PUT')
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
                    placeholder="" name="title[{{$language->abbreviation}}]" value="{{$data['player']->title}}"/>
        </div>
        @endforeach     

        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <!--begin::Label-->
            <label for="number" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">{{helperTrans('admin.number')}}</span>
                <span class="red-star">*</span>
            </label>
        
            <!--end::Label-->
            <input type="number" name="number" value="{{$data['player']->number}}" id="number" required class="form-control form-control-solid"
                    placeholder=""  />
        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
        <label for="" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
            <span class="required mr-1">{{helperTrans('admin.Club')}}</span>
            <span class="red-star">*</span>
        </label>
        <select name="club_id" class="form-control form-control-solid">
            <option value=""></option>
            @foreach($data['clubs'] as $club)
            <option @if($data['player']->club_id == $club->id) selected @endif value="{{ $club->id }}">{{ $club->title }}</option>
            @endforeach
        </select>
        </div> 

        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
        <label for="" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
            <span class="required mr-1">{{helperTrans('admin.Country')}}</span>
            <span class="red-star">*</span>
        </label>
        <select name="country_id" class="form-control form-control-solid">
            <option value=""></option>
            @foreach($data['countries'] as $country)
            <option @if($data['player']->country_id == $country->id) selected @endif value="{{ $country->id }}">{{ $country->title }}</option>
            @endforeach
        </select>
        </div> 


        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
        <label for="" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
            <span class="required mr-1">{{helperTrans('admin.city')}}</span>
            <span class="red-star">*</span>
        </label>
        <select name="city_id" class="form-control form-control-solid">
            <option value=""></option>
            @foreach($data['cities'] as $city)
            <option @if($data['player']->city_id == $city->id) selected @endif value="{{ $city->id }}">{{ $city->title }}</option>
            @endforeach
        </select>
        </div> 

        </div>
</form>


<script>
    $('.dropify').dropify();
</script>
