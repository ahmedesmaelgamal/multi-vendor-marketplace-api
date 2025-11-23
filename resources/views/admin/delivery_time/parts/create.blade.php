<form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{route('deliveryTimes.store')}}">
    @csrf

    {{-- <div class="form-group">
        <label for="name" class="form-control-label">الصورة</label>
        <input type="file" class="dropify" name="image" data-default-file="{{asset('fav.png')}}" accept="image/png, image/svg, image/gif, image/jpeg,image/jpg"/>
        <span class="form-text text-danger text-center">مسموح فقط بالصيغ التالية : png, gif, jpeg, jpg</span>
    </div> --}}
    <div class="form-group">
        <label for="from" class="form-control-label">وقت الإبتداء</label>
        <input type="time" class="form-control" name="from" id="start_time">
    </div>
    <div class="form-group">
        <label for="to" class="form-control-label">وقت الانتهاء</label>
        <input type="time" class="form-control" name="to" id="end_time">
        <span class="form-text text-danger text-center" id="timeError" style="display:none;">وقت الانتهاء يجب أن يكون بعد وقت الإبتداء</span>
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
