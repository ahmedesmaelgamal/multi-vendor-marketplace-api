<form id="updateForm" method="POST" enctype="multipart/form-data" action="{{ route('towns.update', $town->id) }}">
    @csrf
    @method('PUT')
    <div class="col-lg-12">
        <div class="form-group">
            <label class="form-label">التصنيف الرئيسي</label>
            <select name="nationality_id" id="nationality_id" class="form-control select2"
                data-placeholder="--- اختيار التصنيف ---">
                <option disabled selected>--- اختيار التصنيف ---</option>
                @foreach ($Nationalities as $nationality)
                    <option value="{{ $nationality->id }}"
                        {{ $town->nationality_id == $nationality->id ? 'selected' : '' }}>{{ $nationality->title_ar }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="title_ar" class="form-control-label">العنوان باللغة العربية</label>
        <input type="text" class="form-control" name="title_ar" value="{{ $town->title_ar }}">
    </div>
    <div class="form-group">
        <label for="title_en" class="form-control-label">العنوان باللغة الانجليزية</label>
        <input type="text" class="form-control" name="title_en" value="{{ $town->title_en }}">
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
        <button type="submit" class="btn btn-success" id="updateButton">تعديل</button>
    </div>
</form>

<script>
    $('.dropify').dropify()
</script>
