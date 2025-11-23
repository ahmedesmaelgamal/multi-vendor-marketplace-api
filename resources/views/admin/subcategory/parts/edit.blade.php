<link href="{{asset('assets/admin')}}/assets/plugins/select2/select2.min.css" rel="stylesheet"/>

<form id="updateForm" method="POST" enctype="multipart/form-data" action="{{route('subcategories.update',$category->subCategory->id)}}">
    @csrf
    @method('PUT')
    <input type="hidden" name="id" value="{{$category->id}}">
    <div class="form-group">
        <label for="name" class="form-control-label">الصورة</label>
        <input type="file" id="testDrop" class="dropify" name="image" data-default-file="{{$category->subCategory->image}}"/>
    </div>

    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label for="title_ar" class="form-control-label">العنوان (ar)</label>
                <input type="text" class="form-control" name="title_ar" value="{{$category->subCategory->title_ar}}">
            </div>
        </div>

        <div class="col-6">
            <div class="form-group">
                <label for="title_en" class="form-control-label">العنوان (en)</label>
                <input type="text" class="form-control" name="title_en" value="{{$category->subCategory->title_en}}">
            </div>
        </div>
    </div>

    <div class="form-group ">
        <label class="form-label mt-0">القسم</label>
        <select class="form-control select2-show-search" name="category_id" data-placeholder="اختر التصنيف الرئيسي">
            @foreach($categories as $cat)
                <option value="{{$cat->id}}" {{($category->MainCategory->id == $cat->id) ? 'selected' : ''}}>{{$cat->title_ar}}</option>
            @endforeach
        </select>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
        <button type="submit" class="btn btn-success" id="updateButton">تعديل</button>
    </div>
</form>
<script>
    $('.dropify').dropify()
</script>
<script src="{{asset('assets/admin')}}/assets/plugins/select2/select2.full.min.js"></script>
<script src="{{asset('assets/admin')}}/assets/js/select2.js"></script>

