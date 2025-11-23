<form id="updateForm" method="POST" enctype="multipart/form-data"
    action="{{ route('nationalities.update', $nationality->id) }}">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="name" class="form-control-label">الصورة</label>
        <input type="file" id="testDrop" class="dropify" name="image"
            data-default-file="{{ $nationality->image }}" />
    </div>
    <div class="form-group">
        <label for="title_ar" class="form-control-label">العنوان باللغة العربية</label>
        <input type="text" class="form-control" name="title_ar" value="{{ $nationality->title_ar }}">
    </div>
    <div class="form-group">
        <label for="title_en" class="form-control-label">العنوان باللغة الانجليزية</label>
        <input type="text" class="form-control" name="title_en" value="{{ $nationality->title_en }}">
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
        <button type="submit" class="btn btn-success" id="updateButton">تعديل</button>
    </div>
</form>

<script>
    $('.dropify').dropify()
</script>
