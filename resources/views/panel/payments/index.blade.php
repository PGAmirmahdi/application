@extends('panel.layouts.master')
@section('title', 'تراکنش ها')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h6>تراکنش ها</h6>
            </div>
            <form action="{{ route('payments.search') }}" method="get" id="search_form"></form>
            <div class="row mb-3">
                <div class="col-xl-2 xl-lg-2 col-md-3 col-sm-12 mt-2">
                    <input type="text" name="name" class="form-control" placeholder="نام"
                           value="{{ request()->name ?? null }}" form="search_form">
                </div>
                <div class="col-xl-2 xl-lg-2 col-md-3 col-sm-12 mt-2">
                    <input type="text" name="family" class="form-control" placeholder="نام خانوادگی"
                           value="{{ request()->family ?? null }}" form="search_form">
                </div>
                <div class="col-xl-2 xl-lg-2 col-md-3 col-sm-12 mt-2">
                    <select name="status" id="status" class="js-example-basic-single select2-hidden-accessible"
                            data-select2-id="4" tabindex="-1" aria-hidden="true" form="search_form">
                        <option value="all" {{ request()->status == 'all' ? 'selected' : '' }}>وضعیت (همه)</option>
                        <option
                            value="pending" {{ request()->status == 'pending' ? 'selected' : '' }}>{{ \App\Models\Payment::STATUS['pending'] }}</option>
                        <option
                            value="success" {{ request()->status == 'success' ? 'selected' : '' }}>{{ \App\Models\Payment::STATUS['success'] }}</option>
                        <option
                            value="failed" {{ request()->status == 'failed' ? 'selected' : '' }}>{{ \App\Models\Payment::STATUS['failed'] }}</option>
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
                        <th>مبلغ (تومان)</th>
                        <th>وضعیت تراکنش</th>
                        <th>شناسه مرجع</th>
                        <th>شماره تراکنش</th>
                        <th>تاریخ ثبت</th>
                        <th>بررسی مجدد وضعیت</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($payments as $key => $payment)
                        <tr>
                            <td>{{ ++$key }}</td>
                                <td>@if(isset($payment->order_id)){{ $payment->order->user->name }}@elseif(isset($payment->wallet_id)){{ $payment->wallets->users->name }}@endif</td>
                                <td>@if(isset($payment->order_id)){{ $payment->order->user->family }}@elseif(isset($payment->wallet_id)){{ $payment->wallets->users->family }}@endif</td>
                            <td>{{ number_format($payment->amount) }}</td>
                            <td class="status">
                                @if($payment->status == 'success')
                                    <span
                                        class="badge badge-success">{{ \App\Models\Payment::STATUS[$payment->status] }}</span>
                                @elseif($payment->status == 'failed')
                                    <span
                                        class="badge badge-danger">{{ \App\Models\Payment::STATUS[$payment->status] }}</span>
                                @else
                                    <span
                                        class="badge badge-warning">{{ \App\Models\Payment::STATUS[$payment->status] }}</span>
                                @endif
                            </td>
                            <td>{{ str_replace('A000000000000000000000000000', '', $payment->authority) }}</td>
                            <td>{{ $payment->ref_id ?? '---' }}</td>
                            <td>{{ verta($payment->created_at)->format('H:i - Y/m/d') }}</td>
                            <td>
                                <button type="button" class="btn btn-primary btn-floating btn_check"
                                        data-authority="{{ $payment->authority }}" {{ verta($payment->created_at)->addMinutes(10)->formatDatetime() < verta()->formatDatetime() ? '' : 'disabled' }}>
                                    <i class="fa fa-refresh"></i>
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
            <div class="d-flex justify-content-center">{{ $payments->appends(request()->all())->links() }}</div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $(document).on('click', '.btn_check', function () {
                let authority = $(this).data('authority');
                let btn_check = $(this);

                btn_check.attr('disabled', 'disabled');

                // Get the appropriate URL from the server
                $.ajax({
                    url: '/api/v1/get-verify-url', // URL for getting the appropriate verification URL
                    type: 'post',
                    data: { authority },
                    success: function (res) {
                        if (res.error) {
                            alert(res.message);
                            btn_check.removeAttr('disabled');
                            return;
                        }

                        // Perform the verification request to the correct URL
                        $.ajax({
                            url: res.url, // Use the URL received from the previous response
                            type: 'post',
                            data: { authority },
                            success: function (res) {
                                // Handle different error codes and success states
                                if (res.error_code == 100 || res.error_code == 101) {
                                    btn_check.parent().siblings('.status')[0].innerHTML = `<span class="badge badge-success">موفق</span>`;
                                } else if (res.error_code == -51) {
                                    // Handle the session expired case
                                    btn_check.parent().siblings('.status')[0].innerHTML = `<span class="badge badge-danger">ناموفق</span>`;
                                } else {
                                    // Handle other error codes or unexpected responses
                                    btn_check.parent().siblings('.status')[0].innerHTML = `<span class="badge badge-danger">ناموفق</span>`;
                                }
                                btn_check.removeAttr('disabled');
                            },
                            error: function () {
                                btn_check.parent().siblings('.status')[0].innerHTML = `<span class="badge badge-danger">ناموفق</span>`;
                                btn_check.removeAttr('disabled');
                            }
                        });
                    },
                    error: function () {
                        alert('خطا در دریافت URL مناسب');
                        btn_check.removeAttr('disabled');
                    }
                });
            });
        });
    </script>


@endsection
