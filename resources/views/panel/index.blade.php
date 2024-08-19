@extends('panel.layouts.master')
@section('title', 'پنل مدیریت')

@section('styles')
    <style>
        #app_updates ul:not(.list-unstyled) li {
            list-style-type: disclosure-closed
        }

        #app_updates ul {
            line-height: 2rem;
        }
    </style>
@endsection

@section('content')
    <div class="card-body">
        <div class="card-title d-flex justify-content-between align-items-center">
            <h6>آمار</h6>
            <div class="slick-single-arrows">
                <a class="btn btn-outline-light btn-sm">
                    <i class="ti-angle-right"></i>
                </a>
                <a class="btn btn-outline-light btn-sm">
                    <i class="ti-angle-left"></i>
                </a>
            </div>
        </div>
        <div class="row slick-single-item">
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                <div class="card border mb-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div>
                                <div class="icon-block icon-block-sm bg-success icon-block-floating mr-2">
                                    <i class="fa fa-users"></i>
                                </div>
                            </div>
                            <span class="font-size-13">کاربران</span>
                            <h2 class="mb-0 ml-auto font-weight-bold text-success primary-font line-height-30">{{ \App\Models\User::count() }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                <div class="card border mb-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div>
                                <div class="icon-block icon-block-sm bg-secondary icon-block-floating mr-2">
                                    <i class="fa fa-list"></i>
                                </div>
                            </div>
                            <span class="font-size-13">محصولات</span>
                            <h2 class="mb-0 ml-auto font-weight-bold text-secondary primary-font line-height-30">{{ \App\Models\Product::count() }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                <div class="card border mb-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div>
                                <div class="icon-block icon-block-sm bg-info icon-block-floating mr-2">
                                    <i class="fa fa-shopping-cart"></i>
                                </div>
                            </div>
                            <span class="font-size-13">سفارشات</span>
                            <h2 class="mb-0 ml-auto font-weight-bold text-info primary-font line-height-30">{{ \App\Models\Order::count() }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                <div class="card border mb-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div>
                                <div class="icon-block icon-block-sm bg-primary icon-block-floating mr-2">
                                    <i class="fa fa-dollar"></i>
                                </div>
                            </div>
                            <span class="font-size-13">تراکنش ها</span>
                            <h2 class="mb-0 ml-auto font-weight-bold text-primary primary-font line-height-30">{{ \App\Models\Payment::count() }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                <div class="card border mb-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div>
                                <div class="icon-block icon-block-sm bg-primary icon-block-floating mr-2">
                                    <i class="fa fa-message"></i>
                                </div>
                            </div>
                            <span class="font-size-13">تیکت ها</span>
                            <h2 class="mb-0 ml-auto font-weight-bold text-primary primary-font line-height-30">{{ \App\Models\Ticket::count() }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                <div class="card border mb-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div>
                                <div class="icon-block icon-block-sm bg-primary icon-block-floating mr-2">
                                    <i class="fa fa-comment"></i>
                                </div>
                            </div>
                            <span class="font-size-13">نظرات</span>
                            <h2 class="mb-0 ml-auto font-weight-bold text-primary primary-font line-height-30">{{ \App\Models\Comment::count() }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                <div class="card border mb-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div>
                                <div class="icon-block icon-block-sm bg-primary icon-block-floating mr-2">
                                    <i class="fa fa-movie"></i>
                                </div>
                            </div>
                            <span class="font-size-13">ویدیو های محصولات</span>
                            <h2 class="mb-0 ml-auto font-weight-bold text-primary primary-font line-height-30">{{ \App\Models\GuideVideos::count() }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h6>اطلاعات دستگاه‌های ورودی</h6>
            </div>

            <!-- فیلتر جستجو -->
            <form action="{{ route('panel.search') }}" method="get" id="search_form"></form>
            <div class="row mb-3">
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12">
                    <input type="text" name="from" class="form-control" placeholder="نوع درخواست (Web یا App)"
                           value="{{ request()->from ?? null }}" form="search_form">
                </div>
                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12">
                    <button type="submit" class="btn btn-primary" form="search_form">جستجو</button>
                </div>
            </div>

            <!-- جدول اطلاعات وب -->
            <h6>اطلاعات دستگاه‌های وب</h6>
            <div class="table-responsive">
                <table class="table table-striped table-bordered dataTable dtr-inline text-center">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>مراجعه از</th>
                        <th>نام مرورگر</th>
                        <th>اطلاعات دستگاه</th>
                        <th>پلتفرم</th>
                        <th>نسخه اپلیکیشن</th>
                        <th>زمان ثبت</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($infos as $key => $info)
                        @if($info->From === 'web')
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>وب</td>
                                <td>{{ $info->Browser_Name }}</td>
                                <td>{{ Str::limit($info->User_Agent, 60) }}</td>
                                <td>{{ $info->Platform }}</td>
                                <td>{{ $info->App_Version }}</td>
                                <td>{{ verta($info->created_at)->format('H:i - Y/m/d') }}</td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>

            <!-- جدول اطلاعات اپلیکیشن -->
            <h6>اطلاعات دستگاه‌های اپلیکیشن</h6>
            <div class="table-responsive">
                <table class="table table-striped table-bordered dataTable dtr-inline text-center">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>مراجعه از</th>
                        <th>برند</th>
                        <th>مدل</th>
                        <th>نسخه اندروید</th>
                        <th>تولید کننده</th>
                        <th>زمان ثبت</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($infos as $key => $info)
                        @if($info->From !== 'web')
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>اپلیکیشن</td>
                                <td>{{ $info->Brand }}</td>
                                <td>{{ $info->Model }}</td>
                                <td>{{ $info->Android_Version }}</td>
                                <td>{{ $info->Manufactor }}</td>
                                <td>{{ verta($info->created_at)->format('H:i - Y/m/d') }}</td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>

            <!-- صفحه بندی -->
            <div class="d-flex justify-content-center">{{ $infos->appends(request()->all())->links() }}</div>
        </div>
    </div>
@endsection
