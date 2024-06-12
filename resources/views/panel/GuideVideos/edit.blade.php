@php use App\Models\Product; @endphp
@extends('panel.layouts.master')
@section('title', 'ویرایش ویدئو')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h6>ویرایش ویدئو</h6>
            </div>
            <form action="{{ route('GuideVideos.update', $video->id) }}" method="post" enctype="multipart/form-data">
                @method('put')
                @csrf
                <div class="form-row">
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="title">نام محصول<span class="text-danger">*</span></label>
                        <select class="form-control" name="product_id" id="product_id" readonly="">
                            @foreach(Product::all() as $product)
                                <option value="{{ $product->id }}" {{ old('product') == $product->id ? 'selected' : '' }}>{{ $product->title }}</option>
                            @endforeach
                        </select>
                        @error('product_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="title">موضوع<span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" id="title" value="{{$video->title}}">
                        @error('title')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                    <label for="main_video">آپلود ویدئو جدید<span class="text-muted"> (تک انتخابی)</span><span class="text-danger">*</span></label>
                    <input type="file" name="main_video" class="form-control">
                    @error('main_video')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    </div>
                    <div class="col-12 mb-3">
                        <div class="col-xl-3 col-lg-3 col-md-3 mb-3">

                            <label for="">ویدیو آپلود شده</label>
                            <video width="320" height="240" controls>
                                <source src="{{ $video->main_video }}" type="video/mp4">
                                مرورگر شما پشتیبانی نمیکند
                            </video>
                            <input type="hidden" name="main_video_current" value="">
                        </div>
                        <label for="editor-demo2"> توضیحات <span class="text-danger">*</span> </label>
                        <textarea id="editor-demo2" name="text"  >{!! $video->text !!}</textarea>
                        @error('text')
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
    <script src="{{ asset('/assets/js/number2word.js') }}" type="text/javascript"></script>
    {{--    <script>--}}
    {{--        $(document).ready(function () {--}}
    {{--            // add property--}}
    {{--            $('#btn_add').on('click', function () {--}}
    {{--                $('#properties_table tbody').append(`--}}
    {{--                        <tr>--}}
    {{--                            <td><input type="text" name="keys[]" class="form-control" required></td>--}}
    {{--                            <td><input type="text" name="values[]" class="form-control" required></td>--}}
    {{--                            <td><button class="btn btn-danger btn-floating btn_remove" type="button"><i class="fa fa-trash"></i></button></td>--}}
    {{--                        </tr>--}}
    {{--                    `);--}}
    {{--            })--}}
    {{--            // end add property--}}

    {{--            // remove property--}}
    {{--            $(document).on('click','.btn_remove', function () {--}}
    {{--                $(this).parent().parent().remove();--}}
    {{--            })--}}
    {{--            // end remove property--}}
    {{--        })--}}
    {{--    </script>--}}
@endsection
