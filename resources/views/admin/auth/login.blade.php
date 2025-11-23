<!DOCTYPE html>
<html lang="ar">

<head>
    @include('admin.auth.css')
</head>

<body class="g-sidenav-show ">
<form action="{{route('admin.login')}}" method="post" id="LoginForm" enctype="multipart/form-data">
    @csrf
    <input type="text" id="Email" placeholder="ادخل البريد الالكتروني" name="email">
    <label for="Email" onclick="expand(this)">البريد الاكتروني</label>

    <input type="password" id="password" placeholder="ادخل كلمة المرور" name="password">
    <label for="password" onclick="expand(this)">كلمة المرور</label>

    <button id="loginButton">تسجيل الدخول</button>
</form>

@include('admin.auth.js')
</body>
</html>
