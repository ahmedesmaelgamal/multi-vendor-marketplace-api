@extends('admin.layouts.master')
@section('title')
    {{ $setting->title ?? '' }} | أوقات التوصيل
@endsection
@section('page_name')
    أوقات التوصيل
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">أوقات التوصيل</h3>
                    <div class="">
                        <button class="btn btn-secondary btn-icon text-white addBtn">
                            <span>
                                <i class="fe fe-plus"></i>
                            </span> اضافة جديد
                        </button>
                    </div>
                </div>


                <div class="card-body">
                    <select name="category_id" id="category_id" class="form-control col-3 mb-3">
                        <option value="">اختر القسم</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->title_ar }}</option>
                        @endforeach
                    </select>
                    <div class="table-responsive">

                        <!--begin::Table-->
                        <table class="table table-striped table-bordered w-100" id="dataTable">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light">
                                    <th class="min-w-25px">#</th>
                                    <th class="min-w-20px">الاسم</th>
                                    <th class="min-w-20px"> الاسم المستعار </th>
                                    <th class="min-w-20px"> الايميل </th>
                                    <th class="min-w-20px"> الكود </th>
                                    <th class="min-w-20px"> الهاتف </th>
                                    <th class="min-w-20px"> الرقم الضريبي </th>
                                    <th class="min-w-20px"> الصورة </th>
                                    <th class="min-w-20px"> الحالة </th>
                                    <th class="min-w-20px">الفئات</th>
                                    <th class="min-w-20px">العمليات</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!--Delete MODAL -->
        <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">حذف بيانات</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input id="delete_id" name="id" type="hidden">
                        <p>هل انت متأكد من حذف البيانات التالية <span id="title" class="text-danger"></span>؟</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" id="dismiss_delete_modal">
                            الغاء
                        </button>
                        <button type="button" class="btn btn-danger" id="delete_btn">حذف !</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL CLOSED -->

        <!-- Edit MODAL -->
        <div class="modal fade" id="editOrCreate" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="example-Modal3">بيانات التصنيف</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal-body">

                    </div>
                </div>
            </div>
        </div>
        <!-- Edit MODAL CLOSED -->

        <!-- show category modal -->
        <div class="modal fade" id="categoriesModal" data-backdrop="static" tabindex="-1" role="dialog"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">بيانات التصنيف</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="categoriesContent">
                        <form>
                            {{-- <input type="text" name="provider_id" value="55"> --}}

                            <div class="form-group">
                                <label>التصنيفات المرتبطة بالمزود:</label>
                                <ul>

                                </ul>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- show category closed -->

    </div>
    @include('admin.layouts.myAjaxHelper')
@endsection
@section('ajaxCalls')
    <script>
        var columns = [{
                data: 'id',
                name: 'id'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'fake_name',
                name: 'fake_name'
            },
            {
                data: 'email',
                name: 'email'
            },
            {
                data: 'phone_code',
                name: 'phone_code'
            },
            {
                data: 'phone',
                name: 'phone'
            },
            {
                data: 'vat_number',
                name: 'vat_number'
            },
            {
                data: 'image',
                name: 'image'
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'categories',
                name: 'categories'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ]
        // showData('{{ route('providers.index') }}', columns);

        var table = showData({
            url: "{{ route('providers.index') }}",
            data: function(d) {
                d.category_id = $('#category_id').val(); // ابعت الفلتر
            }
        }, columns);

        // لما المستخدم يغيّر الكاتيجوري
        $(document).on('change', '#category_id', function() {
            $('#dataTable').DataTable().ajax.reload();
        });

        // Delete Using Ajax
        deleteScript('{{ route('nationalities.delete') }}');
        // Add Using Ajax
        showAddModal('{{ route('nationalities.create') }}');
        addScript();
        // Edit Using Ajax
        showEditModal('{{ route('nationalities.edit', ':id') }}');
        editScript();
    </script>

    {{-- <script>
        let categoriesRoute = "{{ route('providers.categories', ':id') }}";

        $(document).on('click', '.showCategoriesLink', function() {
            let providerId = $(this).data('id');
            let url = categoriesRoute.replace(':id', providerId);
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    let html = '<ul class="list-group">';
                    response.categories.forEach(function(cat) {
                        html +=
                            '<li class="list-group-item d-flex justify-content-between align-items-center">';
                        html += cat.title_ar;
                        html += '<span class="badge badge-primary badge-pill">✔</span>';
                        html += '</li>';
                    });
                    html += '</ul>';

                    $('#categoriesContent').html(html);
                    $('#categoriesModal').modal('show');
                }

            });
        });
    </script> --}}


    <script>
        // for status
        $(document).on('click', '.statusBtn', function() {
            let id = $(this).data('id');

            var val = $(this).is(':checked') ? 1 : 0;

            let ids = [id];

            $.ajax({
                type: 'POST',
                url: '{{ route('provider.updateColumnSelected') }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'ids': ids,
                },
                success: function(data) {
                    if (data.status === 200) {
                        if (val !== 0) {
                            toastr.success('Success', "");
                        } else {
                            toastr.warning('Success', "");
                        }
                    } else {
                        toastr.error('Error', "");
                    }
                },
                error: function() {
                    toastr.error('Error', "{{ 'something_went_wrong' }}");
                }
            });
        });
        //test
    </script>

    <script>
        let categoriesRoute = "{{ route('providers.categories', ':id') }}";

        $(document).on('click', '.showCategoriesLink', function() {
            let providerId = $(this).data('id');
            let url = categoriesRoute.replace(':id', providerId);

            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    if (response.categories && response.categories.length > 0) {
                        let html = '<ul class="list-group">';
                        response.categories.forEach(function(cat) {
                            html +=
                                '<li class="list-group-item d-flex justify-content-between align-items-center">';
                            html += cat.title_ar ?? 'بدون اسم';
                            html += '<span class="badge badge-primary badge-pill">✔</span>';
                            html += '</li>';
                        });
                        html += '</ul>';
                        $('#categoriesContent').html(html);
                    } else {
                        $('#categoriesContent').html(
                            '<p class="text-muted">لا توجد تصنيفات مرتبطة</p>');
                    }

                    $('#categoriesModal').modal('show');
                },
                error: function(xhr) {
                    console.error("خطأ في جلب التصنيفات", xhr.responseText);
                    alert("حدث خطأ أثناء تحميل التصنيفات");
                }
            });
        });
    </script>

    {{-- <script>
        $(document).on('change', '#category_id', function() {
            let categoryId = $(this).val();
            $.ajax({
                url: "{{ route('providers.index') }}",
                type: 'GET',
                data: {
                    category_id: categoryId
                },
                success: function(response) {
                    $('#sub_category_id').empty();
                    $('#sub_category_id').append('<option value="">Select Sub Category</option>');

                    // response.subCategories.forEach(function(subCat) {
                    //     $('#sub_category_id').append(
                    //         '<option value="' + subCat.id + '">' + subCat.title_ar +
                    //         '</option>'
                    //     );
                    // });
                }
            });
        });
    </script> --}}
@endsection
