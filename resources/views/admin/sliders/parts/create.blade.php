    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{route('sliders.store')}}">
        @csrf
        <div class="form-group">
            <label for="photo" class="form-control-label">الصورة</label>
            <input type="file" class="dropify" name="image" data-default-file="{{asset('fav.png')}}" accept="image/png, image/gif, image/jpeg,image/jpg,image/webp"/>
            <span class="form-text text-danger text-center">مسموح بالصيغ الاتية png, gif, jpeg, jpg,webp</span>
        </div>

        <div class="form-group">
            <label class="form-label">اختيار المنتج</label>
            <select name="product_id" id="product_id" class="form-control select2"
                    data-placeholder="--- اختيار المنتج ---">
                <option disabled selected>--- اختيار المنتج ---</option>
                @foreach($products as $product)
                    <option value="{{$product->id}}">{{$product->title_ar}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="sub_title" class="form-control-label">موعد الانتهاء</label>
            <input type="date" class="form-control" name="end_at" value="{{date('Y-m-d')}}">
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn btn-primary" id="addButton">اضافة</button>
        </div>
    </form>
<script>
    $('.dropify').dropify()
</script>
