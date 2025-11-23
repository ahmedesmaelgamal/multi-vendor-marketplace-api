<title>
    {{($setting->title) ?? 'لوحة التحكم'}} | تسجيل الدخول
</title>
<link rel="shortcut icon" type="image/x-icon" href="{{asset('fav.png')}}">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700&display=swap');
    *,
    *:before,
    *:after {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
    }

    body {
        height: 100vh;
        font-family: "Almarai", sans-serif;
        background: linear-gradient(135deg, #BE3033, #952123);
    }

    form {
        background-color: #202020;
        direction: rtl;
        height: 450px;
        width: 400px;
        position: absolute;
        margin: auto;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        border-radius: 8px;
        box-shadow: 0 20px 25px rgba(0, 0, 0, 0.35);
        padding: 0 50px;
    }

    form * {
        border: none;
        outline: none;
        font-family: "Almarai", sans-serif;
        font-weight: 600;
        font-size: 14px;
        letter-spacing: 0.5px;
    }

    input {
        display: block;
        height: 2px;
        width: 300px;
        position: absolute;
        background-color: #4d4d4d;
        color: #ffffff;
        padding: 0 15px;
        font-weight: 300;
        border-radius: 5px;
        transition: 0.5s all;
    }

    label {
        display: inline-block;
        color: #e5e5e5;
        cursor: pointer;
        font-size: 12px;
        position: absolute;
        transition: 0.5s all;
    }

    #Email {
        bottom: 305px;
    }

    label[for = "Email"] {
        bottom: 310px;
    }

    #password {
        bottom: 195px;
    }

    label[for = "password"] {
        bottom: 200px;
    }

    ::placeholder {
        color: transparent;
    }

    .my-style::placeholder {
        color: #a5a5a5;
    }

    button {
        width: 300px;
        position: absolute;
        bottom: 75px;
        padding: 15px 0;
        background-color: #BE3033;
        color: #ffffff;
        border-radius: 5px;
    }
</style>
@toastr_css
