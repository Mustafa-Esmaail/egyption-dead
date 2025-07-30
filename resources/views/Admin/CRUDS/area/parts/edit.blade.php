<!--begin::Form-->

<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('area.update', $row->id) }}">
    @csrf
    @method('PUT')
    <div class="row g-4">


        <input type="hidden" name="id" value="{{ $row->id }}">


        @foreach (languages() as $index => $language)
            <div class="d-flex flex-column mb-7 fv-row col-sm-6">
                <!--begin::Label-->
                <label for="name_{{ $language->abbreviation }}"
                    class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                    <span class="required mr-1">{{ helperTrans('admin.Name') }}({{ $language->abbreviation }})</span>
                    <span class="red-star">*</span>
                </label>

                <!--end::Label-->
                <input id="name_{{ $language->abbreviation }}" required type="text"
                    class="form-control form-control-solid" placeholder="" name="name[{{ $language->abbreviation }}]"
                    value="{{ $row->getTranslation('name', $language->abbreviation) }}" />
            </div>
        @endforeach
        <!--begin::Input group-->

        <div class="form-group">
            <label for="country_id" class="form-control-label">{{ helperTrans('admin.country') }} </label>
            <select id="country_id" name="country_id" class="form-control">
                @foreach ($countries as $country)
                    <option value="{{ $country->id }}" {{ $row->country_id == $country->id ? 'selected' : '' }}>
                        {{ $country->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="city_id" class="form-control-label">{{ helperTrans('admin.city') }} </label>
            <select t id="city_id" name="city_id" class="form-control">
                @foreach ($cities as $city)
                    <option value="{{ $city->id }}" {{ $row->city_id == $city->id ? 'selected' : '' }}>
                        {{ $city->title }}</option>
                @endforeach
            </select>
        </div>



    </div>
</form>


<script>
    $('.dropify').dropify();
    $('#country_id').on('change', function() {
        var countryId = $(this).val();
        if (countryId) {
            $.ajax({
                url: '{{ route('admin.getCities', ['areaId' => ':countryId']) }}'.replace(':countryId',
                    countryId),
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#city_id').empty();
                    $('#city_id').append(
                        '<option value="">{{ helperTrans('admin.select_city') }}</option>');
                    $.each(data, function(key, city) {
                        $('#city_id').append('<option value="' + city.id + '">' + city
                            .title + '</option>');
                    });
                }
            });
        } else {
            $('#city_id').empty();
            $('#city_id').append('<option value="">{{ helperTrans('admin.select_city') }}</option>');
        }
    });
</script>
