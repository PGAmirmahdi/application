@extends('panel.layouts.master')
@section('title', 'کاربران')
@section('content')
    {{--  addresses Modal  --}}
    <div class="modal fade" id="addressesModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addressesModalLabel">لیست آدرس ها</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="بستن">
                        <i class="ti-close"></i>
                    </button>
                </div>
                <div class="modal-body" style="max-height: 650px; overflow-y: auto">
                    <div class="text-center" id="address_list">
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--  end addresses Modal  --}}
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
                        <th>آدرس ها</th>
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
                                <button class="btn btn-info btn-floating btn_get_address" data-toggle="modal" data-target="#addressesModal" data-id="{{ $user->id }}">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </td>
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
@section('scripts')
    <script>
        var provinces = @json(\App\Models\Province::pluck('name','id'));

        $(document).ready(function () {
            $('.btn_get_address').on('click', function () {
                let user = $(this).data('id');

                $('#address_list').html('<div class="spinner-grow text-primary"></div>');
                $.ajax({
                    type: 'get',
                    url: '/panel/get-addresses/' + user,
                    success: function (res) {
                        $('#address_list').html('');
                        $.each(res.data, function (i, address) {
                            $('#address_list').append(`<div class="shadow p-3 mb-5 bg-white rounded">
                                <p>
                                    <span class="bg-primary" style="width: 30px; display: block">${++i}</span>
                                </p>
                                <p style="font-size: ">عنوان: ${address.title}</p>
                                <p>استان: ${provinces[address.province_id]}</p>
                                <p>شهر: ${address.city}</p>
                                <p>نشانی کامل: ${address.full_address}</p>
                                <p>کد پستی: ${address.postal_code}</p>
                            </div>`)
                        })
                    }
                })
            })
        })
    </script>
@endsection
