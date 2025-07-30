<!--begin::Form-->

<form id="form" enctype="multipart/form-data" method="POST" action="{{route('academy.store')}}">
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

        @foreach(languages() as $index=>$language)

            <div class="d-flex flex-column mb-7 fv-row col-sm-12">
            <!--begin::Label-->
            <label for="desc{{$language->abbreviation}}" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">{{helperTrans('admin.Description')}} ({{$language->abbreviation}})</span>
                <span class="red-star">*</span>
            </label>

            <!--end::Label-->
            <textarea type="text" name="desc[{{$language->abbreviation}}]" value="" id="desc{{$language->abbreviation}}" required class="form-control form-control-solid"
                    placeholder="" > </textarea>
            </div>
        @endforeach
        <div class="form-group">
            <label for="country_id" class="form-control-label">{{ helperTrans('admin.country') }}</label>
            <select id="country_id" name="country_id" class="form-control">
                @foreach($countries as $country)
                    <option value="{{ $country->id }}">{{ $country->title }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="city_id" class="form-control-label">{{ helperTrans('admin.city') }}</label>
            <select id="city_id" name="city_id" class="form-control">
                <option value="">{{ helperTrans('admin.select_city') }}</option>
                {{-- Cities will be loaded here --}}
            </select>
        </div>




    </div>
</form>

<script>
    $('.dropify').dropify();
    $('#country_id').on('change', function () {
        var countryId = $(this).val();
        if (countryId) {
            $.ajax({
                url: '{{ route('admin.getCities', ['areaId' => ':countryId']) }}'.replace(':countryId', countryId),
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#city_id').empty();
                    $('#city_id').append('<option value="">{{  helperTrans('admin.select_city')      }}</option>');
                    $.each(data, function (key, city) {
                        $('#city_id').append('<option value="' + city.id + '">' + city.title + '</option>');
                    });
                }
            });
        } else {
            $('#city_id').empty();
            $('#city_id').append('<option value="">{{  helperTrans('admin.select_city')  }}</option>');
        }
    });
</script>
