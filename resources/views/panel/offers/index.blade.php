@extends('panel.layouts.master')
@section('title', 'آفر ها')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h6>آفر ها</h6>
                <div>
                    <a href="{{ route('offers.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus mr-2"></i>
                        ایجاد آفر محصول
                    </a>
                </div>
            </div>
            <form action="{{ route('offers.index') }}" method="get" id="search_form">
                <div class="row mb-3">
                    <div class="col-xl-2 xl-lg-2 col-md-3 col-sm-12">
                        <input type="text" name="tracking_code" class="form-control" placeholder="محصول"
                               value="{{ request()->products->title ?? '' }}">
                    </div>
                    <div class="col-xl-2 xl-lg-2 col-md-3 col-sm-12">
                        <input type="text" name="sku" class="form-control" placeholder="کد sku"
                               value="{{ request()->products->sku ?? '' }}">
                    </div>
                    <div class="col-xl-2 xl-lg-2 col-md-3 col-sm-12">
                        <input type="text" name="code" class="form-control" placeholder="کد"
                               value="{{ request()->products->code ?? '' }}">
                    </div>
                    <div class="col-xl-2 xl-lg-2 col-md-3 col-sm-12">
                        <input type="text" name="category_name" class="form-control" placeholder="دسته بندی"
                               value="{{ request()->products->category->name ?? '' }}">
                    </div>
                    <div class="col-xl-2 xl-lg-2 col-md-3 col-sm-12">
                        <input type="text" name="percentage" class="form-control" placeholder="درصد آفر"
                               value="{{ request()->percentage ?? '' }}">
                    </div>
                    <div class="col-xl-2 xl-lg-2 col-md-3 col-sm-12">
                        <button type="submit" class="btn btn-primary">جستجو</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped table-bordered dataTable dtr-inline text-center">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>نام محصول</th>
                        <th>کد محصول</th>
                        <th>کد SKU محصول</th>
                        <th>دسته بندی</th>
                        <th>درصد آف</th>
                        <th>قیمت قبل آف</th>
                        <th>قیمت بعد از آف</th>
                        <th>مقدار آف</th>
                    </tr>
                    </thead>
                    <tbody>
                    {{-- اینجا داده‌های تراکنش‌ها قرار می‌گیرند --}}
                    @foreach ($offers as $key => $offer)
                        <tr>
                            <td>{{ $offers->firstItem() + $key }}</td>
                            <td>
                                {{ $offer->products->title }}
                            </td>
                            <td>
                            @if( $offer->products->code )
                                {{ $offer->products->code }}
                            @endif
                            </td>
                            <td>
                            @if( $offer->products->sku )
                                {{ $offer->products->sku }}
                            @endif
                            </td>
                            <td>
                            @if( $offer->products->category->name )
                                {{ $offer->products->category->name }}
                            @endif
                            </td>
                            <td>{{ $offer->percentage }}</td>
                            <td>{{ number_format($offer->price_before) . " تومان" }}</td>
                            <td>{{ number_format($offer->price_after) . " تومان" }}</td>
                            <td>{{ number_format($offer->price_before - $offer->price_after) . " تومان" }}</td>
                            <td>{{ $offer->description }}</td>
                            <td>{{ verta($offer->created_at)->format('H:i - Y/m/d') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">{{ $offers->appends(request()->all())->links() }}</div>
        </div>
    </div>

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#search_form').submit(function (event) {
                event.preventDefault(); // Prevent form submission

                var formData = $(this).serialize(); // Serialize form data

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'GET',
                    data: formData,
                    success: function (response) {
                        // Update the table body with new data
                        var tableBody = $(response).find('.table tbody');
                        $('.table tbody').replaceWith(tableBody);
                    },
                    error: function (xhr) {
                        console.error('Error fetching transactions:', xhr);
                        alert('مشکلی در دریافت اطلاعات وجود دارد');
                    }
                });
            });
        });
    </script>
@endsection
