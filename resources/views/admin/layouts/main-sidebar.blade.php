<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="side-header">
        <a class="header-brand1" href="{{ route('adminHome') }}">
            <img src="{{ asset('fav.png') }}" class="header-brand-img light-logo1" alt="logo">
        </a>
        <!-- LOGO -->
    </div>
    <ul class="side-menu">
        <li>
            <h3>العناصر</h3>
        </li>
        <li class="slide">
            <a class="side-menu__item" href="{{ route('adminHome') }}">
                <i class="icon icon-home side-menu__icon"></i>
                <span class="side-menu__label">الرئيسية</span>
            </a>
        </li>
        <li class="slide">
            <a class="side-menu__item" href="{{ route('admins.index') }}">
                <i class="fe fe-lock side-menu__icon"></i>
                <span class="side-menu__label">المشرفين</span>
            </a>
        </li>


        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="icon icon-basket-loaded side-menu__icon"></i>
                <span class="side-menu__label">المستخدمين</span><i class="angle fa fa-angle-left"></i>
            </a>
            <ul class="slide-menu">
                <li>
                    <a href="{{ route('users.index') }}" class="slide-item" style="font-size: 14px">
                        المستخدمين</a>
                </li>
                <li>
                    <a href="{{ route('representatives.index') }}" class="slide-item" style="font-size: 14px">
                        مناديب التوصيل </a>
                </li>
                <li>
                    <a href="{{ route('providers.index') }}" class="slide-item" style="font-size: 14px">
                        مقدمى الخدمة </a>
                </li>
            </ul>
        </li>



        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="fe fe-align-right side-menu__icon"></i>
                <span class="side-menu__label">التصنيفات</span><i class="angle fa fa-angle-left"></i>
            </a>
            <ul class="slide-menu">
                <li><a href="{{ route('categories.index') }}" class="slide-item" style="font-size: 14px">التصنيفات
                        الرئيسية</a></li>
                <li><a href="{{ route('subcategories.index') }}" class="slide-item" style="font-size: 14px">التصنيفات
                        الفرعية</a></li>
            </ul>
        </li>


        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="fe fe-map-pin side-menu__icon"></i>
                <span class="side-menu__label">الموقع</span><i class="angle fa fa-angle-left"></i>
            </a>
            <ul class="slide-menu">
                <li><a href="{{ route('nationalities.index') }}" class="slide-item" style="font-size: 14px">الدول</a>
                </li>

                <li><a href="{{ route('towns.index') }}" class="slide-item" style="font-size: 14px">
                        المدن</a></li>
            </ul>
        </li>

        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="icon icon-notebook side-menu__icon"></i>
                <span class="side-menu__label">الطلبات</span><i class="angle fa fa-angle-left"></i>
            </a>
            <ul class="slide-menu">
                <li><a href="{{ route('newOrders') }}" class="slide-item" style="font-size: 14px">الطلبات الجديدة</a>
                </li>
                <li><a href="{{ route('currentOrders') }}" class="slide-item" style="font-size: 14px">الطلبات
                        الحالية</a></li>
                <li><a href="{{ route('endedOrders') }}" class="slide-item" style="font-size: 14px">الطلبات السابقة</a>
                </li>
                <li>
                    <a class="slide-item" href="{{ route('reviews.index') }}">
                        تقييمات الطلبات
                    </a>
                </li>
            </ul>
        </li>



        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="icon icon-basket-loaded side-menu__icon"></i>
                <span class="side-menu__label">قائمة المنتجات</span>
                <i class="angle fa fa-angle-left"></i>
            </a>
            <ul class="slide-menu">
                <li><a href="{{ route('products.index') }}" class="slide-item" style="font-size: 14px">المنتجات</a>

                </li>
                <li><a href="{{ route('suggestion.index') }}" class="slide-item" style="font-size: 14px">اقتراحات
                        المنتجات </a>

                </li>
            </ul>



        </li>

        <li class="slide">
            <a class="side-menu__item" href="{{ route('clients.index') }}">
                <i class="fe fe-users side-menu__icon"></i>
                <span class="side-menu__label">قائمة العملاء</span>
            </a>
        </li>



        <li class="slide">
            <a class="side-menu__item" href="{{ route('sliders.index') }}">
                <i class="fe fe-camera side-menu__icon"></i>
                <span class="side-menu__label">الصور المتحركة</span>
            </a>
        </li>





        <li class="slide">
            <a class="side-menu__item" href="{{ route('contact.index') }}">
                <i class="fe fe-mail side-menu__icon"></i>
                <span class="side-menu__label">رسائل العملاء</span>
            </a>
        </li>

        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="icon icon-basket-loaded side-menu__icon"></i>
                <span class="side-menu__label">الإعدادات</span><i class="angle fa fa-angle-left"></i>
            </a>
            <ul class="slide-menu">
                <li>
                    <a href="{{ route('settings.index') }}" class="slide-item" style="font-size: 14px">
                        الاعدادات </a>
                </li>
                <li>
                    <a href="{{ route('deliveryTimes.index') }}" class="slide-item" style="font-size: 14px">أوقات
                        التوصيل</a>
                </li>

                <li>
                    <a href="{{ route('deliveryTimes.index') }}" class="slide-item" style="font-size: 14px">
                        الاعدادات العامة</a>
                </li>
            </ul>
        </li>
        <li class="slide">
            <a class="side-menu__item" href="{{ route('admin.logout') }}">
                <i class="icon icon-lock side-menu__icon"></i>
                <span class="side-menu__label">تسجيل الخروج</span>
            </a>
        </li>


    </ul>
</aside>
