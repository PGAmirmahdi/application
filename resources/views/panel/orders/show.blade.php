@extends('panel.layouts.master')
@section('title', 'مشاهده سفارش')
@section('styles')
    <style>
        .item img{
            width: 50px !important;
        }
        .item .row{
            margin: 2rem 0 2rem 0;
            text-align: center;
        }
        .inactive{
            filter: grayscale(100%) !important;
        }
        .flip-x{
            transform: scaleX(-1);
        }
    </style>
@endsection

@section('content')
    {{--  cancel Modal  --}}
    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelModalLabel">لغو سفارش</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="بستن">
                        <i class="ti-close"></i>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <form action="{{ route('orders.cancel') }}" method="post" id="cancel_form">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order->id }}">

                        <h5>از لغو این سفارش مطمئن هستید؟</h5>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                    <button type="submit" class="btn btn-primary" form="cancel_form">تایید</button>
                </div>
            </div>
        </div>
    </div>
    {{--  end cancel Modal  --}}
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h6>مشاهده سفارش "{{ $order->user->fullName() }}"</h6>
                @if($order->status == 'canceled')
                    <button type="button" class="btn btn-danger disabled" disabled>لغو شده</button>
                @else
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#cancelModal">لغو سفارش</button>
                @endif
            </div>
            @if($order->status != 'canceled')
                <div class="orders">
                <div class="item rounded shadow p-4 mt-4">
                    <div class="d-flex justify-content-between">
                        <h5>وضعیت سفارش</h5>
                        <div class="form-group">
                            <select class="form-control change_status" data-order_id="{{ $order->id }}">
                                @foreach(\App\Models\Order::STATUS as $key => $status)
                                    @if($key != 'pending' && $key != 'canceled')
                                        <option value="{{ $key }}" {{ $key == $order->status ? 'selected' : '' }}>{{ $status }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-4 my-2">
                            <img src="{{ asset('assets/media/image/order/register.png') }}">
                            <small class="d-block">ثبت سفارش</small>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-4 my-2">
                            <img class="{{ in_array($order->status, ['processing','exit','sending','delivered']) ? '' : 'inactive' }}" src="{{ asset('assets/media/image/order/processing.png') }}">
                            <small class="d-block">آماده سازی سفارش</small>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-4 my-2">
                            <img class="{{ in_array($order->status, ['exit','sending','delivered']) ? '' : 'inactive' }}" src="{{ asset('assets/media/image/order/out.png') }}">
                            <small class="d-block">خروج از انبار</small>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-4 my-2">
                            <img class="{{ in_array($order->status, ['sending','delivered']) ? '' : 'inactive' }} flip-x" src="{{ asset('assets/media/image/order/sending.png') }}">
                            <small class="d-block">درحال ارسال</small>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-4 my-2">
                            <img class="{{ in_array($order->status, ['delivered']) ? '' : 'inactive' }} flip-x" src="{{ asset('assets/media/image/order/delivered.png') }}">
                            <small class="d-block">تحویل به مشتری</small>
                        </div>
                    </div>
                    <div class="text-center d-none" id="changing">
                        درحال تغییر وضعیت...
                    </div>
                </div>
            </div>
            @endif
        </div>
        <div class="card-body">
            <div class="item rounded shadow p-4 mt-4">
                <div class="d-flex justify-content-center">
                    <h5>صورت حساب کالا</h5>
                </div>
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                        <p>نام و نام خانوادگی: {{ $order->user->fullName() }}</p>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                        <p>شماره: {{ $order->user->phone }}</p>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                        <p>استان: {{ $order->province->name }}</p>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                    <p>شهر: {{ $order->city }}</p>
                    </div>
                </div>
                <div class="row mt-0">
                    <div class="col-xl-6 col-lg-6 col-md-3 col-sm-12">
                        @if($order->location)
                            @php
                                $location = json_decode($order->location);
                                $lat = $location->lat;
                                $lng = $location->lng;
                            @endphp
                            <a href="https://map.ir/lat/{{$lat}}/lng/{{$lng}}/z/20" class="btn btn-link" target="_blank"><p>نشانی کامل: {{ $order->address }}</p></a>
                        @else
                            <p>نشانی کامل: {{ $order->address }}</p>
                        @endif
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-3 col-sm-12">
                        <p>کد پستی: {{ $order->postal_code }}</p>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="bg-dark">
                            <tr>
                                <th>ردیف</th>
                                <th>عنوان محصول</th>
                                <th>تعداد</th>
                                <th>مبلغ واحد(تومان)</th>
                                <th>مبلغ کل(تومان)</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($order->items as $key => $item)
{{--                                @dd($item)--}}
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $item->product->title }}</td>
                                    <td>{{ $item->count }}</td>
                                    <td>{{ number_format($item->price) }}</td>
                                    <td>{{ number_format($item->total_price) }}</td>
                                </tr>
                            @endforeach
                            <tr style="border-top: 2px solid #000;">
                                <td colspan="4">مبلغ نهایی</td>
                                <td>{{ number_format($order->items()->sum('total_price')) }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $(document).on('change', '.change_status', function () {
                $(this).attr('disabled','disabled')
                $('#changing').removeClass('d-none')

                let order_id = $(this).data('order_id');
                let status = $(this).val();

                $.ajax({
                    type: 'post',
                    url: '/panel/order-change-status',
                    data: {
                        order_id,
                        status
                    },
                    success: function (res) {
                        $('.change_status').removeAttr('disabled')
                        $('#changing').addClass('d-none')

                        $('.card').html($(res).find('.card').html());
                    }
                })
            })
        })
    </script>
@endsection


