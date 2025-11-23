<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Traits\PhotoTrait;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use PhotoTrait;
    public function index()
    {
        $setting = Setting::first();
        return view('admin.setting.index', compact('setting'));
    }


    public function store(Request $request)
    {


        // جلب أول سجل من الإعدادات أو إنشاء واحد جديد
        $setting = Setting::firstOrNew(['id' => 1]);
        // رفع الصورة لو مرفوعة
       




        // تحديث باقي الحقول
        $setting->title = $request->input('title');
        $setting->terms_ar = $request->input('terms_ar');
        $setting->terms_en = $request->input('terms_en');
        $setting->privacy_ar = $request->input('privacy_ar');
        $setting->privacy_en = $request->input('privacy_en');
        $setting->about_us_ar = $request->input('about_us_ar');
        $setting->about_us_en = $request->input('about_us_en');
        $setting->facebook = $request->input('facebook');
        $setting->insta = $request->input('insta');
        $setting->twitter = $request->input('twitter');
        $setting->snapchat = $request->input('snapchat');
        $setting->user_info = $request->input('user_info');
        $setting->provider_info = $request->input('provider_info');
        // حفظ السجل
        $setting->save();

        return redirect()->back()->with('success', 'تم حفظ الإعدادات بنجاح');
    }
}
