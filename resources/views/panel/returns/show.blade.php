@extends('panel.layouts.master')
@section('title', 'مشاهده کالاهای مرجوعی')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h6>مشاهده کالاهای مرجوعی</h6>
                <div>
                    <select name="status" class="form-control" form="status_form" onchange="this.form.submit()">
                        <option value="pending" {{ $return->status == 'pending' ? 'selected' : '' }}>{{ \App\Models\ReturnProduct::STATUS['pending'] }}</option>
                        <option value="returned" {{ $return->status == 'returned' ? 'selected' : '' }}>{{ \App\Models\ReturnProduct::STATUS['returned'] }}</option>
                    </select>
                    <form action="{{ route('returns.change-status', $return->id) }}" method="post" id="status_form">
                        @csrf
                    </form>
                </div>
            </div>
            <div class="row text-center">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12"> نام و نام خانوادگی: {{ $return->user->fullName() }}</div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">شماره سفارش: {{ $return->order_id }}</div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                    @if($return->status == 'returned')
                        وضعیت:
                        <span class="badge badge-success">{{ \App\Models\ReturnProduct::STATUS[$return->status] }}</span>
                    @else
                        وضعیت:
                        <span class="badge badge-warning">{{ \App\Models\ReturnProduct::STATUS[$return->status] }}</span>
                    @endif
                </div>
            </div>
            <div class="table-responsive mt-4">
                <table class="table table-striped table-bordered dataTable dtr-inline text-center">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>تصویر</th>
                        <th>عنوان محصول</th>
                        <th>تعداد</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($return->products)
                        @foreach(json_decode($return->products) as $key => $item)
                            <tr>
                                @php
                                    $product = \App\Models\Product::find($item->product_id);
                                @endphp
                                <td>{{ ++$key }}</td>
                                <td><img src="{{ $product->main_image }}" width="40px"></td>
                                <td>{{ $product->title }}</td>
                                <td>{{ $item->count }}</td>
                            </tr>
                        @endforeach
                    @else
                        @foreach($return->order->items as $key => $item)
                            <tr>
                                @php
                                    $product = \App\Models\Product::find($item->product_id);
                                @endphp
                                <td>{{ ++$key }}</td>
                                <td><img src="{{ $product->main_image }}" width="40px"></td>
                                <td>{{ $product->title }}</td>
                                <td>{{ $item->count }}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                    <tfoot>
                    <tr>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

