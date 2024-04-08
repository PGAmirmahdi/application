@extends('panel.layouts.master')
@section('title', 'دسته بندی ها')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h6>دسته بندی ها</h6>
                <a href="{{ route('categories.create', ['parent_id' => request()->parent_id]) }}" class="btn btn-primary">
                    <i class="fa fa-plus mr-2"></i>
                    ایجاد دسته بندی
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered dataTable dtr-inline text-center">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>تصویر</th>
                        <th>دسته بندی</th>
                        <th>تعداد زیردسته</th>
                        <th>تاریخ ایجاد</th>
                        <th>زیردسته ها</th>
                        <th>ویرایش</th>
                        <th>حذف</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($categories as $key => $category)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td><a href="{{ $category->image }}" target="_blank"><img src="{{ $category->image }}" style="width: 40px"></a></td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->children()->count() }}</td>
                            <td>{{ verta($category->created_at)->format('H:i - Y/m/d') }}</td>
                            <td>
                                <a class="btn btn-info btn-floating" href="{{ route('categories.index', ['parent_id' => $category->id]) }}">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </td>
                            <td>
                                <a class="btn btn-warning btn-floating" href="{{ route('categories.edit', $category->id) }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                            <td>
                                <button class="btn btn-danger btn-floating trashRow" data-url="{{ route('categories.destroy',$category->id) }}" data-id="{{ $category->id }}">
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
            <div class="d-flex justify-content-center">{{ $categories->appends(request()->all())->links() }}</div>
        </div>
    </div>
@endsection


