@extends('panel.layouts.master')
@section('title', 'ایجاد دسته بندی')
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
                <h6>ایجاد دسته بندی</h6>
            </div>
            <form action="{{ route('categories.store', ['parent_id' => request()->parent_id]) }}" method="post">
                @csrf
                <div class="form-row">
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="name">نام دسته بندی<span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" placeholder="پرینتر">
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="parent_category">دسته بندی والد</label>
                        <select name="parent_category" id="parent_category" class="js-example-basic-single select2-hidden-accessible" data-select2-id="4" tabindex="-1" aria-hidden="true">
                            @foreach(\App\Models\Category::all() as $category)
                                <option value="{{ $category->id }}" {{ $category->id == request()->parent_id ? 'selected' : ($category->id == old('parent_category') ? 'selected' : '') }}>{{ $category->name }}</option>
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
