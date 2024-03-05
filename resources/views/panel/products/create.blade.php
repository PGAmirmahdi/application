@extends('panel.layouts.master')
@section('title', 'ایجاد محصول')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h6>ایجاد محصول</h6>
            </div>
            <form action="{{ route('products.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="title">عنوان محصول<span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" id="title" value="{{ old('title') }}" placeholder="پرینتر HP">
                        @error('title')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="sku">کد sku</label>
                        <input type="text" name="sku" class="form-control" id="sku" value="{{ old('sku') }}">
                        @error('sku')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="code">کد محصول<span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control" id="code" value="{{ old('code') }}">
                        @error('code')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="category">دسته بندی <span class="text-danger">*</span></label>
                        <select class="form-control" name="category" id="category">
                            @foreach(\App\Models\Category::all() as $category)
                                <option value="{{ $category->id }}" {{ old('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="price">قیمت (تومان)<span class="text-danger">*</span></label>
                        <input type="text" name="price" class="form-control" id="price" value="{{ old('price') }}">
                        @error('price')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="main_image">تصویر اصلی <span class="text-muted">(تک انتخابی)</span><span class="text-danger">*</span></label>
                        <input type="file" name="main_image" class="form-control" id="main_image" value="{{ old('main_image') }}">
                        @error('main_image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="images">تصاویر دیگر <span class="text-muted">(چند انتخابی)</span></label>
                        <input type="file" name="images[]" class="form-control" id="images" multiple>
                        @error('images.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 mb-3">
                        <label for="editor-demo2"> توضیحات <span class="text-danger">*</span> </label>
                        <textarea id="editor-demo2" name="description">{!! old('description') !!}</textarea>
                        @error('description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-6 col-lg-8 col-md-6 col-sm-12 mb-3">
                        <div class="d-flex justify-content-between mb-3">
                            <label>ویژگی های محصول </label>
                            <button class="btn btn-outline-success" type="button" id="btn_add"><i class="fa fa-plus mr-2"></i> افزودن ویژگی</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped text-center" id="properties_table">
                                <thead>
                                    <tr>
                                        <th>عنوان ویژگی</th>
                                        <th>مقدار ویژگی</th>
                                        <th>حذف</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if(old('keys'))
                                    @foreach(old('keys') as $i => $key)
                                        <tr>
                                            <td>
                                                <input type="text" name="keys[]" class="form-control" value="{{ $key }}" required>
                                            </td>
                                            <td>
                                                <input type="text" name="values[]" class="form-control" value="{{ old('values')[$i] }}" required>
                                            </td>
                                            <td>
                                                <button class="btn btn-danger btn-floating btn_remove" type="button"><i class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td>
                                            <input type="text" name="keys[]" class="form-control" required>
                                        </td>
                                        <td>
                                            <input type="text" name="values[]" class="form-control" required>
                                        </td>
                                        <td>
                                            <button class="btn btn-danger btn-floating btn_remove" type="button"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary" type="submit">ثبت فرم</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('/assets/js/number2word.js') }}" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            // add property
                $('#btn_add').on('click', function () {
                    $('#properties_table tbody').append(`
                        <tr>
                            <td><input type="text" name="keys[]" class="form-control" required></td>
                            <td><input type="text" name="values[]" class="form-control" required></td>
                            <td><button class="btn btn-danger btn-floating btn_remove" type="button"><i class="fa fa-trash"></i></button></td>
                        </tr>
                    `);
                })
            // end add property

            // remove property
                $(document).on('click','.btn_remove', function () {
                    $(this).parent().parent().remove();
                })
            // end remove property
        })
    </script>
@endsection

