@extends('admin.layouts.master')
@section('title')
    {{($setting->title) ?? ''}} | الطلبات الحالية
@endsection
@section('page_name') الطلبات الحالية @endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">طلبات العملاء الحالية</h3>
{{--                    <div class="text-gray">طلبات حالية</div>--}}
                    <div class="">

                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-striped table-bordered w-100" id="dataTable">
                            <thead>
                            <tr class="fw-bolder text-muted bg-light">
                                <th class="min-w-25px">#</th>
                                <th class="min-w-20px">العميل</th>
                                <th class="min-w-20px">الهاتف</th>
                                <th class="min-w-20px">وقت الطلب</th>
                                <th class="min-w-20px">الحالة</th>
                                <th class="min-w-20px">التفاصيل</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @include('admin.layouts.myAjaxHelper')
@endsection
@section('ajaxCalls')
    <script>
        var columns = [
            {data: 'id', name: 'id'},
            {data: 'user_id', name: 'user_id'},
            {data: 'phone', name: 'phone'},
            {data: 'created_at', name: 'created_at'},
            {data: 'status', name: 'status'},
            {data: 'details', name: 'details',orderable: false, searchable: false},
        ]
        showData('{{route('currentOrders')}}', columns);
    </script>
@endsection


