<!--begin::Form-->

<form id="form" enctype="multipart/form-data" method="POST" action="{{route('women-football.update',$data['women']->id)}}">
    @csrf
    @method('PUT')
    <div class="row g-4">

        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <div class="form-group">
                <label for="image" class="form-control-label">{{helperTrans('admin.image')}} </label>
                <input type="file" class="dropify" name="image" data-default-file="{{get_file($data['women']->image)}}" accept="image/*"/>
                <span
                    class="form-text text-muted text-center">{{helperTrans('admin.Only the following formats are allowed: jpeg, jpg, png, gif, svg, webp, avif.')}}</span>

            <input type="hidden" class="remove_file" name="remove_file" value="0"/>

            </div>
        </div>

       

        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
        <div class="form-group">
        <label for="video" class="form-control-label">{{ helperTrans('admin.Video') }}</label>

        <!-- Check if a video exists -->
        @if(get_file($data['women']->video))
            <video 
                controls 
                width="100%" 
                style="margin-bottom: 10px;">
                <source src="{{ get_file($data['women']->video) }}" type="video/mp4">
                
                Your browser does not support the video tag.
            </video>
        @endif

        <div class="form-group border rounded p-2 mb-2">

            <input type="checkbox" name="remove_video" id="remove_video" value="0" class="form-text">
            <label for="remove_video" class="form-control-label">{{ helperTrans('admin.Remove Video') }}</label>
        </div>
        <!-- File input -->
        <input type="file" 
               class="dropif form-control form-control-solid" 
               name="video" 
               accept="video/*"/>
        
        <span class="form-text text-muted text-center">
            {{ helperTrans('admin.Only  formats allowed: mp4, mov, avi, mkv, flv, wmv, webm, m4v, 3gp.') }}
        </span>


    </div>
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
                    placeholder="" name="title[{{$language->abbreviation}}]" 
                    value="{{get_row_translations($data['women'],'title',$language->abbreviation)}}"/>
        </div>
    @endforeach


    @foreach(languages() as $index=>$language)

        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
        <!--begin::Label-->
        <label for="short_desc{{$language->abbreviation}}" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
            <span class="required mr-1">{{helperTrans('admin.Short Description')}}({{$language->abbreviation}})</span>
            <span class="red-star">*</span>
        </label>

        <!--end::Label-->
        <input id="short_desc{{$language->abbreviation}}" required type="text" class="form-control form-control-solid"
                placeholder="" name="short_desc[{{$language->abbreviation}}]" value="{{get_row_translations($data['women'],'short_desc',$language->abbreviation)}}"/>
        </div>
    @endforeach  


   @foreach(languages() as $index=>$language)

    <div class="d-flex flex-column mb-7 fv-row col-sm-6">
       <!--begin::Label-->
       <label for="desc{{$language->abbreviation}}" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
           <span class="required mr-1">{{helperTrans('admin.Description')}} ({{$language->abbreviation}})</span>
           <span class="red-star">*</span>
       </label>
   
       <!--end::Label-->
       <textarea type="text" name="desc[{{$language->abbreviation}}]" value="" id="desc{{$language->abbreviation}}" required class="form-control form-control-solid"
              placeholder="" >{{get_row_translations($data['women'],'desc',$language->abbreviation)}}</textarea>
    </div>
    @endforeach

    <div class="d-flex flex-column mb-7 fv-row col-sm-6">
        <label for="" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
            <span class="required mr-1">{{helperTrans('admin.Category')}}</span>
            <span class="red-star">*</span>
        </label>
        <select name="women_football_category_id" class="form-control form-control-solid">
            <option value=""></option>
            @foreach($data['women_categories'] as $category)
            <option @if($data['women']->women_football_category_id == $category->id) selected @endif value="{{ $category->id }}">{{ $category->title }}</option>
            @endforeach
        </select>
    </div> 

    </div>
    </form>


<script>
    $('.dropify').dropify();
</script>
