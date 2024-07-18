@extends('panel.layouts.master')
@section('title', 'تراکنش ها')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h6>تراکنش ها</h6>
                <div>
                    <a href="{{ route('charging.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus mr-2"></i>
                        ایجاد تراکنش
                    </a>
                </div>
            </div>
            <form action="{{ route('charging.index') }}" method="get" id="search_form">
                <div class="row mb-3">
                    <div class="col-xl-2 xl-lg-2 col-md-3 col-sm-12">
                        <input type="text" name="tracking_code" class="form-control" placeholder="کد پیگیری"
                               value="{{ request()->tracking_code ?? '' }}">
                    </div>
                    <div class="col-xl-2 xl-lg-2 col-md-3 col-sm-12">
                        <select name="type" class="form-control">
                            <option value="">انتخاب کنید</option>
                            <option value="deposit" {{ request()->type == 'deposit' ? 'selected' : '' }}>واریز</option>
                            <option value="withdrawal" {{ request()->type == 'withdrawal' ? 'selected' : '' }}>برداشت</option>
                        </select>
                    </div>
                    <div class="col-xl-3 xl-lg-3 col-md-4 col-sm-12">
                        <input type="text" name="user_family" class="form-control" placeholder="نام خانوادگی کاربر"
                               value="{{ request()->user_family ?? '' }}">
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
                        <th>نام کاربر</th>
                        <th>نوع تراکنش</th>
                        <th>مقدار تراکنش</th>
                        <th>کد پیگیری</th>
                        <th>توضیحات</th>
                        <th>زمان تراکنش</th>
                    </tr>
                    </thead>
                    <tbody>
                    {{-- اینجا داده‌های تراکنش‌ها قرار می‌گیرند --}}
                    @foreach ($chargings as $key => $charging)
                        <tr>
                            <td>{{ $chargings->firstItem() + $key }}</td>
                            <td>
                                    {{ $charging->users->name . ' ' . $charging->users->family }}
                            </td>
                            <td>{{ $charging->type == 'deposit' ? 'واریز' : 'برداشت' }}</td>
                            <td>{{ $charging->amount }}</td>
                            <td>{{ $charging->tracking_code }}</td>
                            <td>{{ $charging->description }}</td>
                            <td>{{ verta($charging->created_at)->format('H:i - Y/m/d') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">{{ $chargings->appends(request()->all())->links() }}</div>
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
