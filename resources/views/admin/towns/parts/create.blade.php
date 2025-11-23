<form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ route('towns.store') }}">
    @csrf
    <div class="col-lg-12">
        <div class="form-group">
            <label class="form-label">التصنيف الرئيسي</label>
            <select name="nationality_id" id="nationality_id" class="form-control select2"
                data-placeholder="--- اختيار التصنيف ---">
                <option disabled selected>--- اختيار التصنيف ---</option>
                @foreach ($Nationalities as $nationality)
                    <option value="{{ $nationality->id }}">{{ $nationality->title_ar }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="title_ar" class="form-control-label">العنوان بالعربي</label>
        <input type="text" class="form-control" name="title_ar" id="title_ar" required>
    </div>
    <div class="form-group">
        <label for="title_en" class="form-control-label">العنوان بالانجليزي</label>
        <input type="text" class="form-control" name="title_en" id="title_en" required>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
        <button type="submit" class="btn btn-primary" id="addButton">اضافة</button>
    </div>
    <script>
        document.getElementById('addForm').addEventListener('submit', function(e) {
            var start = document.getElementById('start_time').value;
            var end = document.getElementById('end_time').value;
            // Convert to integer hours only
            var startHour = start ? parseInt(start.split(':')[0], 10) : null;
            var endHour = end ? parseInt(end.split(':')[0], 10) : null;
            if (startHour !== null && endHour !== null && startHour >= endHour) {
                e.preventDefault();
                document.getElementById('timeError').style.display = 'block';
            } else {
                document.getElementById('timeError').style.display = 'none';
            }
        });
    </script>
</form>
<script>
    $('.dropify').dropify()
</script>
