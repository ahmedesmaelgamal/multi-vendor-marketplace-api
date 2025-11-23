    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{route('sliders.update',$slider->id)}}" >
    @csrf
        @method('PUT')
        <input type="hidden" name="id" value="{{$slider->id}}">
        <div class="form-group">
            <label for="photo" class="form-control-label">الصورة</label>
            <input type="file" class="dropify" name="image" data-default-file="{{$slider->image}}" accept="image/png, image/gif, image/jpeg,image/jpg,image/webp"/>
            <span class="form-text text-danger text-center">مسموح بالصيغ الاتية png, gif, jpeg, jpg,webp</span>
        </div>

        <div class="form-group">
            <label class="form-label">اختيار المنتج</label>
            <select name="product_id" id="product_id" class="form-control select2"
                    data-placeholder="--- اختيار المنتج ---">
                @foreach($products as $product)
                    <option value="{{$product->id}} {{($slider->product_id) ? 'selected' : '' }}">{{$product->title_ar}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="sub_title" class="form-control-label">موعد الانتهاء</label>
            <input type="date" class="form-control" name="end_at" value="{{$slider->end_at}}">
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn btn-success" id="updateButton">تعديل</button>
        </div>
    </form>
<script>
    $('.dropify').dropify()
</script>
