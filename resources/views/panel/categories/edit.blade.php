@extends('panel.layouts.master')
@section('title', 'ویرایش دسته بندی')
@section('styles')
    <style>
        .select2-selection.select2-selection--single{
            padding-left: 10px !important;
        }

        .select2-selection__clear{
            font-size: large;
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h6>ویرایش دسته بندی</h6>
            </div>
            <form action="{{ route('categories.update', $category->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="form-row">
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="name">نام دسته بندی<span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" id="name" value="{{ $category->name }}" placeholder="پرینتر">
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="image">تصویر</label>
                        <input type="file" name="image" class="form-control" id="image">
                        @if($category->image)
                            <a href="{{ $category->image }}" class="btn btn-link" target="_blank">مشاهده تصویر</a>
                        @endif
                        @error('image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="parent_category">دسته بندی والد</label>
                        <select name="parent_category" id="parent_category" class="js-example-basic-single select2-hidden-accessible" data-select2-id="4" tabindex="-1" aria-hidden="true">
                            <option value="">انتخاب کنید...</option>
                            @foreach($categories as $item)
                                <option value="{{ $item->id }}" {{ $item->id == $category->parent_id ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('parent_category')
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
            $('#parent_category').select2({
                allowClear: true,
                placeholder: 'انتخاب کنید...',
            });
        })
    </script>
@endsection
