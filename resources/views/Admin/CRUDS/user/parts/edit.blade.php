<!--begin::Form-->

<form id="form" enctype="multipart/form-data" method="POST" action="{{route('users.update',$user->id)}}">
    @csrf
    @method('PUT')
    <div class="row g-4">


        <input type="hidden" name="id" value="{{$user->id}}">

        <div class="form-group">
            <label for="name" class="form-control-label">{{helperTrans('admin.image')}} </label>
            <input type="file" class="dropify" name="image" data-default-file="{{get_file($user->image)}}"
                   accept="image/*"/>
            <span
                class="form-text text-muted text-center">{{helperTrans('admin.Only the following formats are allowed: jpeg, jpg, png, gif, svg, webp, avif.')}}</span>
        </div>
        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <!--begin::Label-->
            <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">{{helperTrans('admin.Firstname')}}</span>
                <span class="red-star">*</span>
            </label>
            <!--end::Label-->
            <input type="text" required class="form-control form-control-solid" placeholder=" " name="first_name"
                   value="{{$user->first_name}}"/>
        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <!--begin::Label-->
            <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">{{helperTrans('admin.last name')}} <span class="red-star">*</span></span>
            </label>
            <!--end::Label-->
            <input required type="text" class="form-control form-control-solid" placeholder="" name="last_name" value="{{$user->last_name}}"/>
        </div>

        <!--end::Input group-->
        <!--begin::Input group-->
        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <!--begin::Label-->
            <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> {{helperTrans('admin.email')}}</span>
                <span class="red-star">*</span>
            </label>
            <!--end::Label-->
            <input type="email" required class="form-control form-control-solid"
                   placeholder="  {{helperTrans('admin.email')}}"
                   name="email" value="{{$user->email}}"/>
        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <!--begin::Label-->
            <label for="phone" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> Phone</span>
                <span class="red-star">*</span>
            </label>
            <!--end::Label-->
            <input id="phone" type="text" class="form-control form-control-solid" placeholder=" " name="phone"
                   value="{{$user->phone}}"/>
        </div>


            <div class="d-flex flex-column mb-7 fv-row col-sm-6">
                <label for="country" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                    <span class="required mr-1">{{ helperTrans('admin.Country') }}</span>
                    <span class="red-star">*</span>
                </label>
                <select id="country" class="form-control" name="country_id">
                    <option value="">Select . . .</option>
                    @foreach($data['countries'] as $country)
                        <option value="{{ $country->id }}" 
                                data-cities="{{ $country->cities->map(fn($city) => ['id' => $city->id, 'title' => get_row_translations($city, 'title')]) }}"
                                {{ $user->country_id == $country->id ? 'selected' : '' }}>
                            {{ $country->title ?? '' }}
                        </option>
                    @endforeach
                </select>
            </div>


            <div class="d-flex flex-column mb-7 fv-row col-sm-6">
                <!-- City Selection -->
                <label for="city" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                    <span class="required mr-1">{{ helperTrans('admin.City') }}</span>
                    <span class="red-star">*</span>
                </label>
                <select id="city" class="form-control" name="city_id">
                    @foreach($data['cities'] as $city)
                    <option @if($user->city_id == $city->id) selected @endif value="{{ $city->id }}">{{ $city->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex flex-column mb-7 fv-row col-sm-6">
                <label for="Club" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                    <span class="required mr-1">{{ helperTrans('admin.Club') }}</span>
                    <span class="red-star">*</span>
                </label>
                <select id="Club" class="form-control" name="club_id">
                    <option value="">Select . . .</option>
                    @foreach($data['clubs'] as $club)
                        <option value="{{ $club->id }}"
                            @if($club->id == $user->club_id) selected @endif
                                >
                            {{ $club->title ?? '' }}
                        </option>
                    @endforeach
                </select>
            </div>



        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <!--begin::Label-->
            <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> {{helperTrans('admin.password')}}</span>
                <span class="red-star">*</span>
            </label>
            <!--end::Label-->
            <input type="password" class="form-control form-control-solid" placeholder="  " name="password"
                   value=""/>
        </div>





    </div>
</form>
<script>
    $('.dropify').dropify();

</script>
