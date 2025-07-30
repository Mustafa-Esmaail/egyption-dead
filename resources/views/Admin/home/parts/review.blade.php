<div class="card">
    <div class="card-body">
        <table id="student_table" class="table table-bordered dt-responsive nowrap table-striped align-middle"
               style="width:100%">
            <thead>
            <tr>
                <th>#</th>
                <th>القسم</th>
                <th>المدرس</th>
                <th> نوع التقييم</th>
                <th>  النقاط</th>
                <th>  {{trans('admin.created at')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($reviews as $index=>$review)
                <tr>
                    <th>{{$index+1}}</th>
                    <th>{{$review->category->title_ar??''}}</th>
                    <th>{{$review->teacher->name??''}}</th>
                    <th>
                        @if($review->type=='positive')
                            <button class="btn btn-success">تميز</button>
                            @else
                            <button class="btn btn-danger">عقوبة</button>

                    @endif
                    <th>{{abs($review->points)}}</th>
                    <th>{{date('Y/m/d', strtotime($review->created_at))}}</th>

                </tr>
            @endforeach
            </tbody>
        </table>


    </div>
</div>

<script>
    $("#student_table").DataTable({
        processing: true,
        pageLength: 50,
        paging: true,
        // dom: 'Bfrltip',


        bLengthChange: true,
        serverSide: false,
        "ordering": false,
        // order: [
        //     [0, "ASEC"]
        // ],

        // buttons: [
        //     'colvis',
        //     'excel',
        //     'print',
        //     'copy',
        //     'csv',
        //
        //     // 'pdf'
        // ],
        lengthMenu: [
            [25, 50, 100, -1],
            [25, 50, 100, 'All'],
        ],

        searching: true,
        destroy: true,
        info: false,
        sDom: '<"row view-filter"<"col-sm-12"<"float-left"l><"float-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',

        drawCallback: function () {
            $($(".dataTables_wrapper .pagination li:first-of-type"))
                .find("a")
                .addClass("prev");
            $($(".dataTables_wrapper .pagination li:last-of-type"))
                .find("a")
                .addClass("next");

            $(".dataTables_wrapper .pagination").addClass("pagination-sm");
        }
    });

</script>
