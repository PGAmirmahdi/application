@extends('panel.layouts.master')
@section('title', 'ایجاد کاربر')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h6>ایجاد کاربر</h6>
            </div>
            <form action="{{ route('users.store') }}" method="post">
                @csrf
                <div class="form-row">
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="name">نام <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="family">نام خانوادگی <span class="text-danger">*</span></label>
                        <input type="text" name="family" class="form-control" id="family" value="{{ old('family') }}">
                        @error('family')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="national_code">کد ملی</label>
                        <input type="text" name="national_code" class="form-control" id="national_code" value="{{ old('national_code') }}">
                        @error('national_code')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="role">نقش <span class="text-danger">*</span></label>
                        <select class="form-control" name="role" id="role">
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>کاربر</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>ادمین</option>
                        </select>
                        @error('role')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="phone">شماره موبایل <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" id="phone" value="{{ old('phone') }}">
                        @error('phone')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3 d-none">
                        <label for="password">رمزعبور <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" id="password" value="{{ old('password') }}">
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <button class="btn btn-primary" type="submit">ثبت فرم</button>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $('#role').on('change', function () {
                if (this.value == 'admin'){
                    $('#password').parent().removeClass('d-none');
                }else{
                    $('#password').parent().addClass('d-none');
                }
            })
        })
    </script>
@endsection
