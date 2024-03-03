@extends('panel.layouts.master')
@section('title', 'کاربران')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h6>کاربران</h6>
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus mr-2"></i>
                    ایجاد کاربر
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered dataTable dtr-inline text-center" style="width: 100%">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>نام</th>
                        <th>نام خانوادگی</th>
                        <th>شماره موبایل</th>
                        <th>نقش</th>
                        <th>کد ملی</th>
                        <th>تاریخ ایجاد</th>
                        <th>ویرایش</th>
                        <th>حذف</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $key => $user)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->family }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ \App\Models\User::ROLE[$user->role] }}</td>
                            <td>{{ $user->national_code ?? '---' }}</td>
                            <td>{{ verta($user->created_at)->format('H:i - Y/m/d') }}</td>
                            <td>
                                <a class="btn btn-warning btn-floating" href="{{ route('users.edit', $user->id) }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>

                            <td>
                                <button class="btn btn-danger btn-floating trashRow" data-url="{{ route('users.destroy',$user->id) }}" data-id="{{ $user->id }}">
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
            <div class="d-flex justify-content-center">{{ $users->links() }}</div>
        </div>
    </div>
@endsection



