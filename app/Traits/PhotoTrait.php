<?php

namespace App\Traits;

trait  PhotoTrait
{
    function saveImage($photo, $folder)
    {
        // تحقق إن المتغير فعلاً مرفوع (instance of UploadedFile)
        if ($photo instanceof \Illuminate\Http\UploadedFile) {
            $file_extension = $photo->getClientOriginalExtension();
            $file_name = rand(1, 9999) . time() . '.' . $file_extension;
            $path = public_path($folder); // تأكد من حفظ الصورة في المسار الصحيح داخل public
            $photo->move($path, $file_name);
            return $file_name;
        }

        // لو مش صورة مرفوعة (مثلاً string) نرجعها كما هي
        return $photo;
    }
}
