@extends('panel.layouts.master')
@section('title', 'بروزرسانی ها')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h6>بروزرسانی ها</h6>
                <div>
                    <a href="{{ route('updates.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus mr-2"></i>
                        افزودن نسخه جدید
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered dataTable dtr-inline text-center">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>نسخه</th>
                        <th>عنوان نسخه</th>
                        <th>الزامی</th>
                        <th>تاریخ ثبت</th>
                        <th>ویرایش</th>
                        <th>حذف</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($updates as $key => $update)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $update->version }}</td>
                            <td>{{ $update->title }}</td>
                            <td>
                                @if($update->required)
                                    <i class="fa fa-check-square text-success font-size-20"></i>
                                @else
                                    <i class="fa fa-times-square text-danger font-size-20"></i>
                                @endif
                            </td>
                            <td>{{ verta($update->created_at)->format('H:i - Y/m/d') }}</td>
                            <td>
                                <a class="btn btn-warning btn-floating" href="{{ route('updates.edit', $update->id) }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                            <td>
                                <button class="btn btn-danger btn-floating trashRow" data-url="{{ route('updates.destroy',$update->id) }}" data-id="{{ $update->id }}">
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
            <div class="d-flex justify-content-center">{{ $updates->appends(request()->all())->links() }}</div>
        </div>
    </div>
@endsection
