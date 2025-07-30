<!--begin::Form-->

<form id="form" enctype="multipart/form-data" method="POST" action="{{route('vote-choice.store')}}">
    @csrf
    <div class="row g-4">

        <div class="form-group">
            <label for="image" class="form-control-label">{{helperTrans('admin.image')}} </label>
            <input type="file" class="dropify" name="image" data-default-file="{{get_file()}}" accept="image/*"/>
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
                      placeholder="" name="title[{{$language->abbreviation}}]" value=""/>
           </div>
        @endforeach     

        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <label for="" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">{{helperTrans('admin.Vote')}}</span>
                <span class="red-star">*</span>
            </label>
            <select name="vote_id" class="form-control form-control-solid">
                <option value=""></option>
                @foreach($data['votes'] as $vote)
                <option value="{{ $vote->id }}">{{ $vote->title }}</option>
                @endforeach
            </select>
        </div> 


    </div>
</form>

<script>
    $('.dropify').dropify();
</script>
