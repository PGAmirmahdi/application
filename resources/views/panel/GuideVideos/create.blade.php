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
                        <label for="main_video">آپلود ویدئو<span class="text-muted"> (چند انتخابی)</span><span class="text-danger">*</span></label>
                        <input type="file" name="main_video" class="form-control" id="main_video" value="{{ old('main_video') }}">
                        @error('main_video')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 mb-3">
                        <label for="text"> توضیحات <span class="text-danger">*</span> </label>
                        <textarea type="text" id="text" name="text" class="textarea">{!! old('text') !!}</textarea>
                        @error('text')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <button class="btn btn-primary" type="submit">ثبت فرم</button>
                <div class="modal" id="uploadModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">در حال بارگذاری...</h5>
                            </div>
                            <div class="modal-body text-center">
                                <div class="progress-circle">
                                    <svg viewBox="0 0 36 36" class="circular-chart">
                                        <path class="circle-bg"
                                              d="M18 2.0845
                              a 15.9155 15.9155 0 0 1 0 31.831
                              a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                        <path class="circle"
                                              stroke-dasharray="0, 100"
                                              d="M18 2.0845
                              a 15.9155 15.9155 0 0 1 0 31.831
                              a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                        <text x="18" y="20.35" class="percentage">0%</text>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <style>
        .modal-content {
            width: 300px;
        }
        .textarea {
            width: 100%;
            min-height: 200px;
            border-radius: 5px;
            border: 1px solid gainsboro;
            padding: 5px;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1050;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background-color: rgba(0, 0, 0, 0.5);
            outline: 0;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .progress-circle {
            width: 100px;
            margin: auto;
        }
        .circular-chart {
            display: block;
            max-width: 100%;
            max-height: 100%;
        }
        .circle-bg {
            fill: none;
            stroke: #eee;
            stroke-width: 3.8;
        }
        .circle {
            fill: none;
            stroke-width: 2.8;
            stroke-linecap: round;
            stroke: #4caf50;
            transition: stroke-dasharray 0.3s;
        }
        .percentage {
            fill: #666;
            font-family: sans-serif;
            font-size: 0.5em;
            text-anchor: middle;
        }
    </style>
    {{--Jquery--}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            var form = $('#product-form');
            var modal = $('#uploadModal');
            var circle = $('.circle');
            var percentageText = $('.percentage');

            form.on('submit', function (event) {
                event.preventDefault();

                var formData = new FormData(this);
                var textContent = $('#text').val();
                formData.set('text', textContent);

                modal.css('display', 'flex');

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        console.log("قبل از ارسال");
                        var percentVal = '0%';
                        percentageText.text(percentVal);
                    },
                    xhr: function () {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function (evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = Math.round((evt.loaded / evt.total) * 100);
                                var percentVal = percentComplete + '%';
                                var strokeDashArray = percentComplete + ', 100';
                                circle.attr('stroke-dasharray', strokeDashArray);
                                percentageText.text(percentVal);
                            }
                        }, false);
                        return xhr;
                    },
                    success: function (response) {
                        modal.hide();
                        alert(response.success);
                        window.location.href = "{{ route('GuideVideos.index') }}";
                    },
                    error: function (xhr) {
                        modal.hide();
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
