@php use Illuminate\Support\Str; @endphp
@extends('panel.layouts.master')
@section('title', 'کیف پول ها')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h6>کیف پول ها</h6>
                <div>
                    <a href="{{ route('wallet.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus mr-2"></i>
                        ساخت کیف پول
                    </a>
                </div>
            </div>
            <form action="{{ route('wallet.search') }}" method="get" id="search_form">
                <div class="row mb-3">
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 mb-2">
                        <input type="text" name="user_name" class="form-control" placeholder="نام دارنده کیف پول"
                               value="{{ request()->user_name ?? null }}" form="search_form">
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 mb-2">
                        <input type="text" name="user_phone" class="form-control" placeholder="شماره تلفن دارنده کیف پول"
                               value="{{ request()->user_phone ?? null }}" form="search_form">
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 mb-2">
                        <input type="text" name="balance" class="form-control" placeholder="موجودی کیف پول"
                               value="{{ request()->balance ?? null }}" form="search_form">
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 mb-2">
                        <button type="submit" class="btn btn-primary" form="search_form">جستجو</button>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-striped table-bordered dataTable dtr-inline text-center">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>نام صاحب</th>
                        <th>موجودی</th>
                        <th>زمان ساخت</th>
                        <th>حذف</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($wallets as $key => $wallet)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>
                                @if ($wallet->user)
                                    {{ $wallet->user->name . " " .  $wallet->user->family }}
                                @else
                                    کاربر ناشناس
                                @endif
                            </td>
                            <td>{{ number_format($wallet->balance) . ' ' . 'تومان' }}</td>
                            <td>{{ verta($wallet->created_at)->format('H:i - Y/m/d') }}</td>
                            <td>
                                <button class="btn btn-danger btn-floating trashRow"
                                        data-url="{{ route('wallet.destroy', $wallet->id) }}"
                                        data-id="{{ $wallet->id }}">
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
            <div class="d-flex justify-content-center">{{ $wallets->appends(request()->all())->links() }}</div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('assets/js/lazysizes.min.js') }}"></script>
@endsection
