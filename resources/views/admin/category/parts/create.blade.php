<form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{route('categories.store')}}">
    @csrf

    <div class="form-group">
        <label for="name" class="form-control-label">الصورة</label>
        <input type="file" class="dropify" name="image" data-default-file="{{asset('fav.png')}}" accept="image/png, image/svg, image/gif, image/jpeg,image/jpg"/>
        <span class="form-text text-danger text-center">مسموح فقط بالصيغ التالية : png, gif, jpeg, jpg</span>
    </div>
    <div class="form-group">
        <label for="title_ar" class="form-control-label">العنوان باللغة العربية</label>
        <input type="text" class="form-control" name="title_ar">
    </div>
    <div class="form-group">
        <label for="title_en" class="form-control-label">العنوان باللغة الانجليزية</label>
        <input type="text" class="form-control" name="title_en">
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
        <button type="submit" class="btn btn-primary" id="addButton">اضافة</button>
    </div>
</form>
<script>
    $('.dropify').dropify()
</script>
