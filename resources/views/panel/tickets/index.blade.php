@extends('panel.layouts.master')
@section('title', 'تیکت ها')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h6>تیکت ها</h6>
                    <a href="{{ route('tickets.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus mr-2"></i>
                        ثبت تیکت
                    </a>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered dataTable dtr-inline text-center">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>فرستنده</th>
                        <th>عنوان تیکت</th>
                        <th>شماره تیکت</th>
                        <th>وضعیت</th>
                        <th>تاریخ ایجاد</th>
                        <th>مشاهده</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($tickets as $key => $ticket)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $ticket->sender->fullName() }}</td>
                            <td>{{ $ticket->title }}</td>
                            <td>{{ $ticket->code }}</td>
                            <td>
                                @if($ticket->status == 'closed')
                                    <span class="badge badge-success">بسته شده</span>
                                @else
                                    <span class="badge badge-warning">درحال بررسی</span>
                                @endif
                            </td>
                            <td>{{ verta($ticket->created_at)->format('H:i - Y/m/d') }}</td>
                            <td>
                                <a class="btn btn-info btn-floating" href="{{ route('tickets.show', $ticket->id) }}">
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
            <div class="d-flex justify-content-center">{{ $tickets->appends(request()->all())->links() }}</div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('assets/js/lazysizes.min.js') }}"></script>
@endsection
