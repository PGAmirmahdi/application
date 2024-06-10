{{--Button to get back into the app--}}
    <!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>بازگشت به سایت</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body onload="load()">
<div class="No1">
    @if($Status == "OK")
        <i style="color: #3aff55;font-size: 125px" class="material-icons">check_circle</i>
        <h3>پرداخت موفق</h3>
        <p>پرداخت شما با موفقیت ثبت شد،لطفا برای ادامه از دکمه زیر اقدام کنید</p>
    @else
        <i style="color: #de0a0f;font-size: 125px" class="material-icons">error</i>
        <h3>پرداخت ناموفق</h3>
        <p>متاسفانه پرداخت شما ناموفق بود،لطفا از دکمه زیر برای ادامه اقدام کنید</p>
    @endif
    <button type="button"
            class="text-white bg-purple-700 hover:bg-purple-800 focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900" onclick="GoToSite()">رفتن به سایت</button>
</div>
<style>
    @font-face {
        font-family: 'Estedad';
        src: url("{{asset('assets/fonts/Estedad-Black.woff2')}}") format("woff2"), url("{{asset('assets/fonts/Estedad-Black.ttf')}}") format("truetype"), url("{{asset('assets/fonts/Estedad-Black.woff')}}") format("woff");
    ;
        font-weight: normal;
        font-style: normal;
    }
    *{
        font-family: Estedad, Estedad;
        overflow: hidden;
    }
    i{
        transform: translateX(100px);
        opacity: 0;
        visibility: hidden;
        transition: .3s;
    }
    h3{
        transition: .3s;
        transform: translateX(100px);
        opacity: 0;
        visibility: hidden;
        transition-delay:.1s;
        font-size: 30px !important;
    }
    p{
        transition: .3s;
        font-weight: lighter;
        font-size: 14px;
        transform: translateX(100px);
        opacity: 0;
        visibility: hidden;
        transition-delay:.2s;
    }
    button{
        transform: translateX(100px);
        opacity: 0;
        visibility: hidden;
        transition-delay:.3s;
        width: 50%;
        height: 50px;
        font-size: 22px;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        background-color: #a63aff;
        border-radius: 10px;
        border:none;
        box-shadow: 0px 1px 1px 0px #a63aff;
        transition: .2s ease-in-out;
    }
    i.load{
        visibility: visible;
        opacity: 1;
        transform: translateX(0px);
        transition: .5s;
    }
    h3.load{
        visibility: visible;
        opacity: 1;
        transform: translateX(0px);
        transition: .5s;
        transition-delay: .1s;
    }
    p.load{
        visibility: visible;
        opacity: 1;
        transform: translateX(0px);
        transition: .5s;
        transition-delay: .3s;
    }
    button.load{
        visibility: visible;
        opacity: 1;
        transform: translateX(0px);
        transition: .5s;
        transition-delay: .5s;
    }
    button:hover{
        transition: .2s ease-in-out;
        background-color: #7b00ff;
    }
    .No1{
        width: 100%;
        height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 10px;
    }@media only screen and (max-width:765px){
        p{
            font-size: 12px !important;
        }
        button{
            width: 80%;
            height: 40px;
            font-size: 18px;
        }
    }

</style>
{{--Link JS--}}
<script src="{{asset('assets/js/BackToApp.js')}}"></script>
<script>setTimeout(() => {  location.href="intent://artintoner.com?authority={{$authority}}#Intent;scheme=https;package=com.example.artintoner;end"; }, 3000);</script>
</body>
</html>
