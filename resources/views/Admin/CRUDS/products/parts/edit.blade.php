<!--begin::Form-->

<form id="form" enctype="multipart/form-data" method="POST"
    action="{{ route('products.update', $data['product']->id) }}">
    @csrf
    @method('PUT')
    <div class="row g-4">

        <div class="d-flex flex-column mb-7 fv-row col-sm-12">
            <div class="form-group">
                <label for="image" class="form-control-label">{{ helperTrans('admin.image') }} </label>
                <input type="file" class="dropify" name="image"
                    data-default-file="{{ get_file($data['product']->image) }}" accept="image/*" />
                <span
                    class="form-text text-muted text-center">{{ helperTrans('admin.Only the following formats are allowed: jpeg, jpg, png, gif, svg, webp, avif.') }}</span>
            </div>
        </div>

        <!-- <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <div class="form-group">
                <label for="video" class="form-control-label">{{ helperTrans('admin.Video') }} </label>
                <input type="file" class="dropify" name="video" data-default-file="{{ get_file() }}" accept="video/*"/>
                <span
                    class="form-text text-muted text-center">{{ helperTrans('admin.Only the following formats are allowed: mp4, mov, avi, mkv, flv, wmv, webm, m4v, 3gp.') }}</span>
            </div>
        </div> -->

        @foreach (languages() as $index => $language)
            <div class="d-flex flex-column mb-7 fv-row col-sm-6">
                <!--begin::Label-->
                <label for="title{{ $language->abbreviation }}"
                    class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                    <span class="required mr-1">{{ helperTrans('admin.title') }}({{ $language->abbreviation }})</span>
                    <span class="red-star">*</span>
                </label>

                <!--end::Label-->
                <input id="title{{ $language->abbreviation }}" type="text" class="form-control form-control-solid"
                    placeholder="" name="title[{{ $language->abbreviation }}]"
                    value="{{ get_row_translations($data['product'], 'title', $language->abbreviation) }}" />
            </div>
        @endforeach
        @foreach (languages() as $index => $language)
            <div class="d-flex flex-column mb-7 fv-row col-sm-12">
                <!--begin::Label-->
                <label for="desc{{ $language->abbreviation }}"
                    class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                    <span class="required mr-1">{{ helperTrans('admin.Description') }}
                        ({{ $language->abbreviation }})</span>
                    <span class="red-star">*</span>
                </label>

                <!--end::Label-->
                <textarea type="text" name="desc[{{ $language->abbreviation }}]" value=""
                    id="desc{{ $language->abbreviation }}" class="form-control form-control-solid" placeholder="">{{ get_row_translations($data['product'], 'desc', $language->abbreviation) }}</textarea>
            </div>
        @endforeach
        {{-- @foreach (languages() as $index => $language)
            <div class="d-flex flex-column mb-7 fv-row col-sm-6">
                <!--begin::Label-->
                <label for="address{{ $language->abbreviation }}"
                    class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                    <span
                        class="required mr-1">{{ helperTrans('admin.address') }}({{ $language->abbreviation }})</span>
                    <span class="red-star">*</span>
                </label>

                <!--end::Label-->
                <input id="address{{ $language->abbreviation }}" required type="text"
                    class="form-control form-control-solid" placeholder=""
                    name="address[{{ $language->abbreviation }}]"
                    value="{{ get_row_translations($data['product'], 'address', $language->abbreviation) }}" />
            </div>
        @endforeach --}}
        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <label for="" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">{{ helperTrans('admin.User') }}</span>
                <span class="red-star">*</span>
            </label>
            <select name="user_id" class="form-control form-control-solid">
                <option value=""></option>
                @foreach ($data['users'] as $user)
                    <option @if ($data['product']->user_id == $user->id) selected @endif value="{{ $user->id }}">
                        {{ $user->first_name . ' ' . $user->last_name }}</option>
                @endforeach
            </select>
        </div>


        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <!--begin::Label-->
            <label for="price" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">{{ helperTrans('admin.price') }}</span>
                <span class="red-star">*</span>
            </label>

            <!--end::Label-->
            <input type="number" name="price" value="{{ $data['product']->price }}" id="price" required
                class="form-control form-control-solid" placeholder="" />
        </div>


        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <label for="" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">{{ helperTrans('admin.Category') }}</span>
                <span class="red-star">*</span>
            </label>
            <select name="product_category_id" class="form-control form-control-solid">
                <option value=""></option>
                @foreach ($data['categories'] as $category)
                    <option @if ($data['product']->product_category_id == $category->id) selected @endif value="{{ $category->id }}">
                        {{ $category->title }}</option>
                @endforeach
            </select>
        </div>

    </div>
</form>


<script>
    $('.dropify').dropify();
</script>
