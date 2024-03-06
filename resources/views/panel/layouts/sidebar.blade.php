<!-- begin::navigation -->
<div class="navigation">
    <div class="navigation-icon-menu" style="overflow-y: auto">
        <ul>
            <li class="{{ active_sidebar(['panel','users','users/create','users/{user}/edit']) ? 'active' : '' }}" data-toggle="tooltip" title="داشبورد">
                <a href="#navigationDashboards" title="داشبوردها">
                    <i class="icon ti-dashboard"></i>
                </a>
            </li>
            <li class="{{ active_sidebar(['products','products/create','products/{product}/edit','search/products','categories','categories/create','categories/{category}/edit']) ? 'active' : '' }}" data-toggle="tooltip" title="محصولات">
                <a href="#navigationProducts" title="محصولات">
                    <i class="icon ti-list"></i>
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
        <ul id="navigationProducts" class="{{ active_sidebar(['products','products/create','products/{product}/edit','search/products','categories','categories/create','categories/{category}/edit']) ? 'navigation-active' : '' }}">
            <li class="navigation-divider">محصولات</li>
            <li>
                <a class="{{ active_sidebar(['products','products/create','products/{product}/edit','search/products']) ? 'active' : '' }}" href="{{ route('products.index') }}">محصولات</a>
            </li>
            <li>
                <a class="{{ active_sidebar(['categories','categories/create','categories/{category}/edit']) ? 'active' : '' }}" href="{{ route('categories.index') }}">دسته بندی ها</a>
            </li>
        </ul>
    </div>
</div>
<!-- end::navigation -->
