<!-- begin::navigation -->
<div class="navigation">
    <div class="navigation-icon-menu" style="overflow-y: auto">
        <ul>
            <li class="{{ active_sidebar(['panel','users','users/create','users/{user}/edit']) ? 'active' : '' }}" data-toggle="tooltip" title="داشبورد">
                <a href="#navigationDashboards" title="داشبوردها">
                    <i class="icon ti-dashboard"></i>
                </a>
            </li>
            <li class="{{ active_sidebar(['products','products/create','products/{product}/edit','search/products','categories','categories/create','categories/{category}/edit','comments']) ? 'active' : '' }}" data-toggle="tooltip" title="محصولات">
                <a href="#navigationProducts" title="محصولات">
                    <i class="icon ti-list"></i>
                </a>
            </li>
            <li class="{{ active_sidebar(['orders','orders/{order}','search/orders','payments','payments/{payment}','search/payments','delivery-days','returns','returns/{return}']) ? 'active' : '' }}" data-toggle="tooltip" title="سفارشات">
                <a href="#navigationOrders" title="سفارشات">
                    <i class="icon ti-shopping-cart"></i>
                </a>
            </li>
            <li class="{{ active_sidebar(['tickets','tickets/create','tickets/{ticket}/edit','search/tickets','bugs']) ? 'active' : '' }}" data-toggle="tooltip" title="پشتیبانی">
                <a href="#navigationTickets" title="پشتیبانی">
                    <i class="icon ti-comment-alt"></i>
                </a>
            </li>
            <li class="{{ active_sidebar(['banners','updates','updates/create','updates/{update}/edit']) ? 'active' : '' }}" data-toggle="tooltip" title="موارد بیشتر">
                <a href="#navigationMore" title="موارد بیشتر">
                    <i class="icon ti-more"></i>
                </a>
            </li>
        </ul>
        <ul>
            <li data-toggle="tooltip" title="ویرایش پروفایل">
                <a href="" class="go-to-page">
                    <i class="icon ti-settings"></i>
                </a>
            </li>
            <li data-toggle="tooltip" title="خروج">
                <a href="{{ route('logout') }}" class="go-to-page" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                    <i class="icon ti-power-off"></i>
                </a>
            </li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </ul>
    </div>
    <div class="navigation-menu-body">
        <ul id="navigationDashboards" class="{{ active_sidebar(['panel','users','users/create','users/{user}/edit']) ? 'navigation-active' : '' }}">
            <li class="navigation-divider">داشبورد</li>
            <li>
                <a class="{{ active_sidebar(['panel']) ? 'active' : '' }}" href="{{ route('panel') }}">پنل</a>
            </li>
            <li>
                <a class="{{ active_sidebar(['users','users/create','users/{user}/edit']) ? 'active' : '' }}" href="{{ route('users.index') }}">کاربران</a>
            </li>
        </ul>
        <ul id="navigationProducts" class="{{ active_sidebar(['products','products/create','products/{product}/edit','search/products','categories','categories/create','categories/{category}/edit','comments','GuideVideos','GuideVideos/create','GuideVideos/{GuideVideo}/edit']) ? 'navigation-active' : '' }}">
            <li class="navigation-divider">محصولات</li>
            <li>
                <a class="{{ active_sidebar(['products','products/create','products/{product}/edit','search/products']) ? 'active' : '' }}" href="{{ route('products.index') }}">محصولات</a>
            </li>
            <li>
                <a class="{{ active_sidebar(['comments']) ? 'active' : '' }}" href="{{ route('comments.index') }}">نظرات</a>
            </li>
            <li>
                <a class="{{ active_sidebar(['categories','categories/create','categories/{category}/edit']) ? 'active' : '' }}" href="{{ route('categories.index') }}">دسته بندی ها</a>
            </li>
            <li>
                <a class="{{ active_sidebar(['GuideVideos','GuideVideos/create','GuideVideos/{GuideVideo}/edit']) ? 'active' : '' }}" href="{{ route('GuideVideos.index') }}">ویدئو های محصولات</a>
            </li>
        </ul>
        <ul id="navigationOrders" class="{{ active_sidebar(['orders','orders/{order}','search/orders','payments','payments/{payment}','search/payments','delivery-days','returns','returns/{return}']) ? 'navigation-active' : '' }}">
            <li class="navigation-divider">سفارشات</li>
            <li>
                <a class="{{ active_sidebar(['orders','orders/{order}','search/orders']) ? 'active' : '' }}" href="{{ route('orders.index') }}">سفارشات</a>
            </li>
            <li>
                <a class="{{ active_sidebar(['returns','returns/{return}']) ? 'active' : '' }}" href="{{ route('returns.index') }}">مرجوعی ها</a>
            </li>
            <li>
                <a class="{{ active_sidebar(['payments','payments/{payment}','search/payments']) ? 'active' : '' }}" href="{{ route('payments.index') }}">تراکنش ها</a>
            </li>
            <li>
                <a class="{{ active_sidebar(['delivery-days']) ? 'active' : '' }}" href="{{ route('delivery-days.index') }}">روز های تحویل سفارش</a>
            </li>
        </ul>
        <ul id="navigationTickets" class="{{ active_sidebar(['tickets','tickets/create','tickets/{ticket}/edit','search/tickets','bugs']) ? 'navigation-active' : '' }}">
            <li class="navigation-divider">پشتیبانی</li>
            <li>
                <a class="{{ active_sidebar(['tickets','tickets/create','tickets/{ticket}/edit','search/tickets']) ? 'active' : '' }}" href="{{ route('tickets.index') }}">تیکت ها</a>
            </li>
            <li>
                <a class="{{ active_sidebar(['bugs']) ? 'active' : '' }}" href="{{ route('bugs.index') }}">گزارشات خرابی</a>
            </li>
        </ul>
        <ul id="navigationMore" class="{{ active_sidebar(['banners','updates','updates/create','updates/{update}/edit']) ? 'navigation-active' : '' }}">
            <li class="navigation-divider">موارد بیشتر</li>
            <li>
                <a class="{{ active_sidebar(['banners']) ? 'active' : '' }}" href="{{ route('banners.index') }}">بنر صفحه اصلی</a>
            </li>
            <li>
                <a class="{{ active_sidebar(['updates','updates/create','updates/{update}/edit']) ? 'active' : '' }}" href="{{ route('updates.index') }}">بروزرسانی ها</a>
            </li>
        </ul>
    </div>
</div>
<!-- end::navigation -->
