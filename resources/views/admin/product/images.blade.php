@extends('admin.layouts.master')
@section('title')
    {{($setting->title) ?? ''}} | صور المنتجات
@endsection
@section('page_name') صور المنتجات @endsection
@section('content')
    <div class="row">
        <div class="demo-gallery card">
            <div class="card-header">
                <h3 class="card-title">الصور المضافة ل{{$product->title_ar}}</h3>
                <div class="">
                    <button class="btn btn-secondary btn-icon text-white addBtn" data-toggle="modal" data-target="#add_modal">
									<span>
										<i class="fe fe-plus"></i>
									</span> اضافة جديد
                    </button>
                </div>
            </div>
            <d  iv class="card-body">
                <ul id="lightgallery" class="list-unstyled row" lg-uid="lg0">
                    @foreach($images as $image)
                    <li class="col-xs-6 col-sm-4 col-md-4 col-xl-3 mb-5 border-bottom-0" id="image{{$image->id}}"
                        data-responsive="{{$image->image}}" data-src="{{$image->image}}"
                        data-sub-html="<h4></h4><p></p>"
                        lg-event-uid="&amp;1">
                        <a href="#">
                            <img class="img-responsive" src="{{$image->image}}" alt="صورة" style="height: 140px">
                        </a>
                        <button class='btn btn-danger mt-1 deleteBtn'  data-id="{{$image->id}}" style="width: inherit" href = ''>حــذف  <i class='fa fa-trash'></i> </button>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <!--Delete MODAL -->
        <div class="modal fade" id="add_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">اضافة صورة</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form action="{{route('addProductPhoto')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="hidden" value="{{$product->id}}" name="product_id">
                                <label for="name" class="form-control-label">الصورة</label>
                                <input type="file" multiple required class="dropify" name="image[]" data-default-file="{{asset('fav.png')}}" accept="image/png, image/svg, image/gif, image/jpeg,image/jpg"/>
                                <span class="form-text text-danger text-center">مسموح فقط بالصيغ التالية : png, gif, jpeg, jpg</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal" id="">
                                الغاء
                            </button>
                            <button type="submit" class="btn btn-primary" id="">اضافة</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- MODAL CLOSED -->

    </div>
@endsection
@section('js')
    <script>
        $('.deleteBtn').click(function (){
            var id = $(this).attr('data-id'),
                routeOfDelete = "{{route('deleteProductImage')}}";
            $.ajax({
                type: 'POST',
                url: routeOfDelete,
                data: {
                    '_token': "{{csrf_token()}}",
                    'id': id,
                },
                success: function (data) {
                    if (data.status === 200) {
                        $('#image'+data.id).hide();
                        toastr.success(data.message)
                    } else {
                        toastr.error("حدث خطأ ما يرجي اعادة المحاولة")
                    }
                }
            });
        });
    </script>
@endsection


