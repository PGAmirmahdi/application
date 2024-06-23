@extends('panel.layouts.master')
@section('title', 'کد تخفیف')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h6>کد تخفیف</h6>
                <a href="{{ route('coupons.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus mr-2"></i>
                    ایجاد کد تخفیف
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered dataTable dtr-inline text-center">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>عنوان</th>
                        <th>کد</th>
                        <th>درصد تخفیف</th>
                        <th>تاریخ ایجاد</th>
                        <th>ویرایش</th>
                        <th>حذف</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($coupons as $key => $coupon)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $coupon->title }}</td>
                            <td>{{ $coupon->code }}</td>
                            <td>% {{ $coupon->amount_pc }}</td>
                            <td>{{ verta($coupon->created_at)->format('H:i - Y/m/d') }}</td>
                            <td>
                                <a class="btn btn-warning btn-floating" href="{{ route('coupons.edit', $coupon->id) }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                            <td>
                                <button class="btn btn-danger btn-floating trashRow"
                                        data-url="{{ route('coupons.destroy',$coupon->id) }}"
                                        data-id="{{ $coupon->id }}">
                                    <i class="fa fa-trash"></i>
                                </button>
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
            <div class="d-flex justify-content-center">{{ $coupons->links() }}</div>
        </div>
    </div>
@endsection


