<!--begin::Form-->

<form id="form" enctype="multipart/form-data" method="POST" action="{{route('varChooses.update',$data['row']->id)}}">
    @csrf
    @method('PUT')
    <div class="row g-4">


        <input type="hidden" name="id" value="{{$data['row']->id}}">

        
        @foreach(languages() as $index=>$language)
           
           <div class="d-flex flex-column mb-7 fv-row col-sm-6">
               <!--begin::Label-->
               <label for="choose{{$language->abbreviation}}" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                   <span class="required mr-1">{{helperTrans('admin.choose')}}({{$language->abbreviation}})</span>
                   <span class="red-star">*</span>
               </label>
           
               <!--end::Label-->
               <input id="choose{{$language->abbreviation}}" required type="text" class="form-control form-control-solid"
                      placeholder="" name="choose[{{$language->abbreviation}}]" value="{{$data['row']->getTranslation('choose', $language->abbreviation)}}"/>
           </div>
           
        @endforeach     
        <!--begin::Input group-->
      
        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <label for="" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">{{helperTrans('admin.Var')}}</span>
                <span class="red-star">*</span>
            </label>
            <select name="var_id" class="form-control form-control-solid">
                <option value=""></option>
                @foreach($data['vars'] as $var)
                <option value="{{ $var->id }}" @if($data['row']->var_id == $var->id) selected @endif>{{ $var->desc }}</option>
                @endforeach
            </select>
        </div> 



    </div>
</form>

