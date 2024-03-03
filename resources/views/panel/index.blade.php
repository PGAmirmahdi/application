@extends('panel.layouts.master')
@section('title', 'پنل مدیریت')

@section('styles')
    <style>
        #app_updates ul:not(.list-unstyled) li{
            list-style-type: disclosure-closed
        }
        #app_updates ul{
            line-height: 2rem;
        }
    </style>
@endsection
@section('content')
    <div class="card">
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
                                    <h2 class="mb-0 ml-auto font-weight-bold text-success primary-font line-height-30">0</h2>
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
                                            <i class="fa fa-users"></i>
                                        </div>
                                    </div>
                                    <span class="font-size-13">مشتریان</span>
                                    <h2 class="mb-0 ml-auto font-weight-bold text-secondary primary-font line-height-30">0</h2>
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
                                            <i class="fa fa-p"></i>
                                        </div>
                                    </div>
                                    <span class="font-size-13">محصولات</span>
                                    <h2 class="mb-0 ml-auto font-weight-bold text-info primary-font line-height-30">0</h2>
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
                                            <i class="fa fa-shopping-cart"></i>
                                        </div>
                                    </div>
                                    <span class="font-size-13">سفارش مشتری</span>
                                    <h2 class="mb-0 ml-auto font-weight-bold text-primary primary-font line-height-30">0</h2>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
@endsection

