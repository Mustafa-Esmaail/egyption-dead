@extends('Admin.layouts.inc.app')
@section('title')
    @php

    $pageName = 'users';
    $pageUrl = 'users';
    $tdList = ['name','email','country','city','club','is_active','image','created_at','action'];

    @endphp
    {{helperTrans("admin.$pageName")}}
@endsection
@section('css')

@endsection
@section('toolbar')

@include('Admin.layouts.inc.toolbar')

@endsection

@section('content')

    @include('Admin.layouts.inc.tableHeader',['pageName'=>$pageName,'tdList'=>$tdList])
    @include('Admin.layouts.inc.modal',['pageName'=>$pageName,'modalWidth'=>'modal-xl'])

@endsection
@section('js')

    @include('Admin.layouts.inc.ajax',['url'=>$pageUrl])
    <script>
        $(document).on('change', '.activeBtn', function () {
            var id = $(this).attr('data-id');

            $.ajax({
                type: 'GET',
                url: "{{route('admin.active.user')}}",
                data: {
                    id: id,
                },

                success: function (res) {
                    if (res['status'] == true) {

                        toastr.success("{{helperTrans('admin.operation accomplished successfully')}}")
                        // $('#table').DataTable().ajax.reload(null, false);
                    } else {
                        // location.reload();

                    }
                },
                error: function (data) {
                    // location.reload();
                }
            });


        })
    </script>
    <script>
        $(document).on('change','#check_all',function () {
            var checked = $(this).is(':checked');

            if (checked){
                $('.checkbox').prop('checked', true);
            }
            else {
                $('.checkbox').prop('checked', false);
            }
        })

        $(document).on('change', '#country',function(){
                const selectedCountry = this.options[this.selectedIndex];
                const cities = JSON.parse(selectedCountry.getAttribute('data-cities') || '[]');
                const citySelect = document.getElementById('city');

                citySelect.innerHTML = '<option value="">Select . . .</option>';

                cities.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city.id;
                    option.textContent = city.title;
                    citySelect.appendChild(option);
                });
            })
    </script>


@endsection
