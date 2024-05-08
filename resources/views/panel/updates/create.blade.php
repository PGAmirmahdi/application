@extends('panel.layouts.master')
@section('title', 'افزودن نسخه')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h6>افزودن نسخه</h6>
            </div>
            <form action="{{ route('updates.store') }}" method="post">
                @csrf
                <div class="form-row">
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="version">نسخه<span class="text-danger">*</span></label>
                        <input type="text" name="version" class="form-control" id="version" value="{{ old('version') }}" placeholder="1.0.0">
                        @error('version')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="title">عنوان نسخه<span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" id="title" value="{{ old('title') }}" placeholder="قابلیت جستجوی کارتریج پرینتر">
                        @error('title')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <div class="custom-control custom-checkbox mt-4 text-center">
                            <input type="checkbox" name="required" id="required" class="custom-control-input" {{ old('required') ? 'checked' : '' }}>
                            <label class="custom-control-label" for="required">بروزرسانی الزامی</label>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="editor-demo2"> توضیحات <span class="text-danger">*</span> </label>
                        <textarea id="editor-demo2" name="description">{!! old('description') !!}</textarea>
                        @error('description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <button class="btn btn-primary" type="submit">ثبت فرم</button>
            </form>
        </div>
    </div>
@endsection
