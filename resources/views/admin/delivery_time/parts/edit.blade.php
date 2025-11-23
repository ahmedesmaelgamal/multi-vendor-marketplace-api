<form id="updateForm" method="POST" enctype="multipart/form-data"
    action="{{ route('deliveryTimes.update', $deliveryTime->id) }}">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="from" class="form-control-label">وقت الإبتداء</label>
        <input type="time" class="form-control" name="from" id="start_time"
            value="{{ \Carbon\Carbon::createFromTimestampMs($deliveryTime->from)->format('H:i') }}" required>
    </div>

    <div class="form-group">
        <label for="to" class="form-control-label">وقت الانتهاء</label>
        <input type="time" class="form-control" name="to" id="end_time"
            value="{{ \Carbon\Carbon::createFromTimestampMs($deliveryTime->to)->format('H:i') }}" required>
        <span class="form-text text-danger text-center" id="timeError" style="display:none;">
        </span>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
        <button type="submit" class="btn btn-primary">تحديث</button>
    </div>
</form>

<script>
    // تحقق من الوقت قبل إرسال الفورم
    document.getElementById('updateForm').addEventListener('submit', function(e) {
        var start = document.getElementById('start_time').value;
        var end = document.getElementById('end_time').value;

        if (start && end) {
            var startTime = parseInt(start.replace(':', ''), 10);
            var endTime = parseInt(end.replace(':', ''), 10);

            if (startTime >= endTime) {
                e.preventDefault(); // منع الإرسال
                document.getElementById('timeError').style.display = 'block';
            } else {
                document.getElementById('timeError').style.display = 'none';
            }
        }
    });
</script>
