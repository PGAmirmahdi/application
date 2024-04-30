@extends('panel.layouts.master')
@section('title', 'مرجوعی ها')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h6>مرجوعی ها</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered dataTable dtr-inline text-center">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>نام و نام خانوادگی</th>
                        <th>شماره سفارش</th>
                        <th>وضعیت</th>
                        <th>تاریخ ثبت</th>
                        <th>مشاهده</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($returns as $key => $return)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $return->user->fullName() }}</td>
                            <td><strong><u>{{ $return->order_id }}</u></strong></td>
                            <td>
                                @if($return->status == 'returned')
                                    <span class="badge badge-success">{{ \App\Models\ReturnProduct::STATUS[$return->status] }}</span>
                                @else
                                    <span class="badge badge-warning">{{ \App\Models\ReturnProduct::STATUS[$return->status] }}</span>
                                @endif
                            </td>
                            <td>{{ verta($return->created_at)->format('H:i - Y/m/d') }}</td>
                            <td>
                                <a class="btn btn-info btn-floating" href="{{ route('returns.show', $return->id) }}">
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
            <div class="d-flex justify-content-center">{{ $returns->appends(request()->all())->links() }}</div>
        </div>
    </div>
@endsection

