@php use App\Models\Product; @endphp
@extends('panel.layouts.master')
@section('title', 'ویرایش ویدئو')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h6>ویرایش ویدئو</h6>
            </div>
            <form id="product-form" action="{{ route('GuideVideos.update', $video->id) }}" method="post" enctype="multipart/form-data">
                @method('put')
                @csrf
                <div class="form-row">
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="product_id">نام محصول<span class="text-danger">*</span></label>
                        <select class="form-control" name="product_id" id="product_id" readonly>
                            @foreach(Product::all() as $product)
                                <option value="{{ $product->id }}"
                                        {{ old('product_id') == $product->id ? 'selected' : '' }} disabled>{{ $product->title }}</option>
                            @endforeach
                        </select>
                        @error('product_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="title">موضوع<span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" id="title" value="{{ $video->title }}">
                        @error('title')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="main_video">آپلود ویدئو<span class="text-muted"> (تک انتخابی)</span><span
                                class="text-danger">*</span></label>
                        <input type="file" name="main_video" class="form-control" id="main_video"
                               value="{{ old('main_video') }}">
                        @error('main_video')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="">ویدیو آپلود شده</label>
                        <video width="320" height="240" controls>
                            <source src="{{ $video->main_video }}" type="video/mp4">
                            مرورگر شما پشتیبانی نمیکند
                        </video>
                        <input type="hidden" name="main_video_current" value="">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="editor-demo2"> توضیحات <span class="text-danger">*</span> </label>
                        <textarea id="editor-demo2" name="text">{!! $video->text !!}</textarea>
                        @error('text')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <button class="btn btn-primary" type="submit">ثبت فرم</button>
                    <div class="progress">
                        <div class="bar"></div>
                        <div class="percent">0%</div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <style>
        .progress {
            direction: ltr;
            position: relative;
            width: 100%;
            margin-top: 10px;
            height: 25px;
        }

        .bar {
            width: 0;
            background-color: #9700ff;
            height: 100%;
        }

        .percent {
            position: absolute;
            display: inline-block;
            left: 50%;
            color: #333;
            transform: translateX(-50%);
            line-height: 25px;
        }
    </style>
    {{--Jquery--}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            console.log("فایل آماده");

            var bar = $(".bar");
            var percent = $(".percent");

            $('#product-form').ajaxForm({
                beforeSend: function () {
                    console.log("قبل از ارسال");
                    var percentVal = '0%';
                    bar.width(percentVal);
                    percent.html(percentVal);
                },
                uploadProgress: function (event, position, total, percentComplete) {
                    console.log("در حال آپلود", percentComplete);
                    var percentVal = percentComplete + '%';
                    bar.width(percentVal);
                    percent.html(percentVal);
                },
                success: function (response) {
                    console.log("آپلود موفق", response);
                    var percentVal = '100%';
                    bar.width(percentVal);
                    percent.html(percentVal);
                    alert("فایل با موفقیت آپلود شد");
                },
                complete: function (xhr) {
                    console.log("Complete", xhr.responseText);
                },
                error: function (xhr) {
                    console.log("Error", xhr.responseText);
                    alert("مشکلی در ارسال فایل وجود دارد");
                }
            });
        });
    </script>
@endsection
