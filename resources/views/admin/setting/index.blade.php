@extends('admin.layouts.master')

@section('title', 'Settings')

@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mb-3">General Settings</h4>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('admin.settings.store') }}" method="POST">
                @csrf

                <div class="row">

                    <div class="col-md-12 mb-3">
                        <label>العنوان</label>
                        <input type="text" name="title" class="form-control" value="{{ value($setting->title ?? '') }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>الشروط والأحكام بالعربي</label>
                        <textarea name="terms_ar" class="form-control" rows="5">{{ $setting->terms_ar ?? '' }}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>الشروط والأحكام بالانجليزى</label>
                        <textarea name="terms_en" class="form-control" rows="5">{{ $setting->terms_en ?? '' }}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label> سياسة الخصوصية بالعربي</label>
                        <textarea name="privacy_ar" class="form-control" rows="5">{{ $setting->privacy_ar ?? '' }}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label> سياسة الخصوصية بالانجليزى</label>
                        <textarea name="privacy_en" class="form-control" rows="5"{{ $setting->privacy_en ?? '' }}></textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label> معلومات عنا بالعربي </label>
                        <textarea name="about_us_ar" class="form-control" rows="5">{{ $setting->about_us_ar ?? '' }}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label> معلومات عنا بالانجليزى</label>
                        <textarea name="about_us_en" class="form-control" rows="5">{{ $setting->about_us_en ?? '' }}</textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>FaceBook</label>
                        <input type="text" name="facebook" class="form-control" value="{{ $setting->facebook ?? '' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>insta</label>
                        <input type="text" name="insta" class="form-control" value="{{ $setting->insta ?? '' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>twitter</label>
                        <input type="text" name="twitter" class="form-control" value="{{ $setting->twitter ?? '' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>snapchat</label>
                        <input type="text" name="snapchat" class="form-control" value="{{ $setting->snapchat ?? '' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>user_info</label>
                        <input type="text" name="user_info" class="form-control"
                            value="{{ $setting->user_info ?? '' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>provider_info</label>
                        <input type="text" name="provider_info" class="form-control"
                            value="{{ $setting->provider_info ?? ''}}">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>image</label>
                        <input type="file" name="image" class="dropify">
                    </div>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">حفظ التغيرات </button>
                </div>
            </form>
        </div>
    </div>
@endsection
