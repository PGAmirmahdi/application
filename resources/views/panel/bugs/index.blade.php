@extends('panel.layouts.master')
@section('title', 'گزارشات خرابی')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h6>گزارشات خرابی</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered dataTable dtr-inline text-center">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>نام و نام خانوادگی</th>
                        <th>شماره تماس</th>
                        <th>عنوان</th>
                        <th>توضیحات</th>
                        <th>نسخه</th>
                        <th>تاریخ ثبت</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($bugs as $key => $bug)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $bug->person }}</td>
                            <td>{{ $bug->phone }}</td>
                            <td>{{ $bug->title }}</td>
                            <td>{{ $bug->description }}</td>
                            <td>{{ $bug->app_version }}</td>
                            <td>{{ verta($bug->created_at)->format('H:i - Y/m/d') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <div class="d-flex justify-content-center">{{ $bugs->appends(request()->all())->links() }}</div>
        </div>
    </div>
@endsection
