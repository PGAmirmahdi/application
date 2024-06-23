@php use App\Models\Product; @endphp
@extends('panel.layouts.master')
@section('title', 'ایجاد ویدئو')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h6>ایجاد ویدئو</h6>
            </div>
            <form id="product-form" action="{{ route('GuideVideos.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="product_id">نام محصول<span class="text-danger">*</span></label>
                        <select class="form-control" name="product_id" id="product_id">
                            @foreach(Product::all() as $product)
                                <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>{{ $product->title }}</option>
                            @endforeach
                        </select>
                        @error('product_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="title">موضوع<span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" id="title" value="{{ old('title') }}">
                        @error('title')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="main_video">آپلود ویدئو<span class="text-muted"> (تک انتخابی)</span><span class="text-danger">*</span></label>
                        <input type="file" name="main_video" class="form-control" id="main_video" value="{{ old('main_video') }}">
                        @error('main_video')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 mb-3">
                        <label for="text"> توضیحات <span class="text-danger">*</span> </label>
                        <input type="text" id="text" name="text">{!! old('text') !!}
                        @error('text')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <button class="btn btn-primary" type="submit">ثبت فرم</button>
                <div class="progress">
                    <div class="bar"></div>
                    <div class="percent">0%</div>
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
        }
        .percent {
            position: absolute;
            display: inline-block;
            left: 50%;
            color: #333;
        }
    </style>
    {{--Jquery--}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            var form = $('#product-form');
            var bar = $('.bar');
            var percent = $('.percent');

            form.on('submit', function (event) {
                event.preventDefault(); // Prevent default form submission

                var formData = new FormData(this); // Gather form data

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        console.log("قبل از ارسال");
                        var percentVal = '0%';
                        bar.width(percentVal);
                        percent.html(percentVal);
                    },
                    xhr: function () {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function (evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = Math.round((evt.loaded / evt.total) * 100);
                                var percentVal = percentComplete + '%';
                                bar.width(percentVal);
                                percent.html(percentVal);
                            }
                        }, false);
                        return xhr;
                    },
                    success: function (response) {
                        console.log("آپلود موفق", response);
                        var percentVal = '100%';
                        bar.width(percentVal);
                        percent.html(percentVal);
                        alert(response.success);

                        window.location.href = "{{ route('GuideVideos.index') }}";
                    },
                    error: function (xhr) {
                        console.log("Error", xhr);
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            var errorMessage = "خطا در اعتبارسنجی:<br>";
                            for (var key in errors) {
                                if (errors.hasOwnProperty(key)) {
                                    errorMessage += "- " + errors[key][0] + "<br>";
                                }
                            }
                            alert(errorMessage);
                        } else {
                            alert("مشکلی در ارسال فایل وجود دارد");
                        }
                    }
                });
            });
        });
    </script>
@endsection
