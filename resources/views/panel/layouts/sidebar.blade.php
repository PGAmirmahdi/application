<!-- begin::navigation -->
<div class="navigation">
    <div class="navigation-icon-menu" style="overflow-y: auto">
        <ul>
            <li class="{{ active_sidebar(['panel','users','users/create','users/{user}/edit','roles','roles/create','roles/{role}/edit', 'tasks','tasks/create','tasks/{task}/edit', 'tasks/{task}', 'notes','notes/create','notes/{note}/edit','leaves','leaves/create','leaves/{leave}/edit','reports','reports/create','reports/{report}/edit','software-updates','software-updates/create','software-updates/{software_update}/edit']) ? 'active' : '' }}" data-toggle="tooltip" title="داشبورد">
                <a href="#navigationDashboards" title="داشبوردها">
                    <i class="icon ti-dashboard"></i>
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
        <ul id="navigationDashboards" class="{{ active_sidebar(['panel','users','users/create','users/{user}/edit','roles','roles/create','roles/{role}/edit', 'tasks','tasks/create','tasks/{task}/edit', 'tasks/{task}', 'notes','notes/create','notes/{note}/edit', 'leaves','leaves/create','leaves/{leave}/edit','reports','reports/create','reports/{report}/edit','software-updates','software-updates/create','software-updates/{software_update}/edit']) ? 'navigation-active' : '' }}">
            <li class="navigation-divider">داشبورد</li>
            <li>
                <a class="{{ active_sidebar(['panel']) ? 'active' : '' }}" href="{{ route('panel') }}">پنل</a>
            </li>
        </ul>
        <ul id="navigationProducts" class="{{ active_sidebar(['categories','categories/create','categories/{category}/edit','products','products/create','products/{product}/edit','search/products','printers','printers/create','printers/{printer}/edit','coupons','coupons/create','coupons/{coupon}/edit','prices-list', 'price-history','search/printers','artin-products','other-prices-list']) ? 'navigation-active' : '' }}">
            <li class="navigation-divider">محصولات</li>
        </ul>
        <ul id="navigationInvoices" class="{{ active_sidebar(['invoices','invoices/create','invoices/{invoice}/edit','search/invoices','factors','factors/create','factors/{factor}/edit','search/factors','sale-reports','sale-reports/create','sale-reports/{sale_report}/edit','search/sale-reports', 'invoice-action/{invoice}','orders-status/{invoice}','price-requests','price-requests/create','price-requests/{price_request}/edit','price-requests/{price_request}','buy-orders','buy-orders/create','buy-orders/{buy_order}/edit','buy-orders/{buy_order}','search/buy-orders']) ? 'navigation-active' : '' }}">
            <li class="navigation-divider">سفارشات و فروش</li>
        </ul>
        <ul id="navigationPackets" class="{{ active_sidebar(['packets','packets/create','packets/{packet}/edit','search/packets']) ? 'navigation-active' : '' }}">
            <li class="navigation-divider">بسته های ارسالی</li>
        </ul>
        <ul id="navigationCustomers" class="{{ active_sidebar(['customers','customers/create','customers/{customer}/edit','search/customers','foreign-customers','foreign-customers/create','foreign-customers/{foreign_customer}/edit','search/foreign-customers']) ? 'navigation-active' : '' }}">
            <li class="navigation-divider">مشتریان</li>
        </ul>
        <ul id="navigationTickets" class="{{ active_sidebar(['tickets','tickets/create','tickets/{ticket}/edit','search/tickets','sms-histories','sms-histories/{sms_history}']) ? 'navigation-active' : '' }}">
            <li class="navigation-divider">پشتیبانی و تیکت</li>
        </ul>
        <ul id="navigationInventory" class="{{ active_sidebar(['inventory','inventory/create','inventory/{inventory}/edit','search/inventory','inventory-reports','inventory-reports/create','inventory-reports/{inventory_report}/edit','warehouses','warehouses/create','warehouses/{warehouse}/edit','search/inventory-reports','guarantees','guarantees/create','guarantees/{guarantee}/edit']) ? 'navigation-active' : '' }}">
            <li class="navigation-divider">انبار</li>
        </ul>
        <ul id="navigationExitDoor" class="{{ active_sidebar(['exit-door','exit-door/create','exit-door/{exit_door}/edit','search/exit-door']) ? 'navigation-active' : '' }}">
            <li class="navigation-divider">درب خروج</li>
        </ul>
{{--        <ul id="navigationBot" class="{{ active_sidebar(['bot-profile']) ? 'navigation-active' : '' }}">--}}
{{--            <li class="navigation-divider">ربات تلگرام</li>--}}
{{--            <li>--}}
{{--                <a class="{{ active_sidebar(['bot-profile']) ? 'active' : '' }}" href="{{ route('bot.profile') }}">مشخصات ربات</a>--}}
{{--            </li>--}}
{{--        </ul>--}}
    </div>
</div>
<!-- end::navigation -->
