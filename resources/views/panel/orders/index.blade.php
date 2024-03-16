@extends('panel.layouts.master')
@section('title', 'سفارشات')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h6>سفارشات</h6>
            </div>
            <form action="{{ route('orders.search') }}" method="get" id="search_form"></form>
            <div class="row mb-3">
                <div class="col-xl-2 xl-lg-2 col-md-3 col-sm-12 mt-2">
                    <input type="text" name="name" class="form-control" placeholder="نام" value="{{ request()->name ?? null }}" form="search_form">
                </div>
                <div class="col-xl-2 xl-lg-2 col-md-3 col-sm-12 mt-2">
                    <input type="text" name="family" class="form-control" placeholder="نام خانوادگی" value="{{ request()->family ?? null }}" form="search_form">
                </div>
                <div class="col-xl-2 xl-lg-2 col-md-3 col-sm-12 mt-2">
                    <input type="text" name="phone" class="form-control" placeholder="شماره تماس" value="{{ request()->phone ?? null }}" form="search_form">
                </div>
                <div class="col-xl-2 xl-lg-2 col-md-3 col-sm-12 mt-2">
                    <select name="status" id="status" class="js-example-basic-single select2-hidden-accessible" data-select2-id="4" tabindex="-1" aria-hidden="true" form="search_form">
                        <option value="all" {{ request()->status == 'all' ? 'selected' : '' }}>وضعیت (همه)</option>
                        @foreach(\App\Models\Order::STATUS as $key => $value)
                            <option value="{{ $key }}" {{ $key == request()->status ? 'selected' : ($key == old('status') ? 'selected' : '') }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xl-2 xl-lg-2 col-md-3 col-sm-12 mt-2">
                    <button type="submit" class="btn btn-primary" form="search_form">جستجو</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered dataTable dtr-inline text-center">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>نام</th>
                        <th>نام خانوادگی</th>
                        <th>شماره تماس</th>
                        <th>استان</th>
                        <th>شهر</th>
                        <th>وضعیت سفارش</th>
                        <th>تاریخ ثبت</th>
                        <th>مشاهده</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $key => $order)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>{{ $order->user->family }}</td>
                            <td>{{ $order->user->phone }}</td>
                            <td>{{ $order->province->name }}</td>
                            <td>{{ $order->city }}</td>
                            @if($order->status == 'pending')
                                <td>
                                    <span class="badge badge-warning">{{ \App\Models\Order::STATUS[$order->status] }}</span>
                                </td>
                            @elseif($order->status == 'canceled')
                                <td>
                                    <span class="badge badge-danger">{{ \App\Models\Order::STATUS[$order->status] }}</span>
                                </td>
                            @else
                                <td>
                                    <span class="badge badge-success">{{ \App\Models\Order::STATUS[$order->status] }}</span>
                                </td>
                            @endif
                            <td>{{ verta($order->created_at)->format('H:i - Y/m/d') }}</td>
                            <td>
                                <a class="btn btn-info btn-floating" href="{{ route('orders.show', $order->id) }}">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <div class="d-flex justify-content-center">{{ $orders->appends(request()->all())->links() }}</div>
        </div>
    </div>
@endsection
