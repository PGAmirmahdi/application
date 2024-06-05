@extends('panel.layouts.master')
@section('title', 'بنر صفحه اصلی')
@section('styles')
    <link rel="stylesheet" href="/vendors/slick/slick.css" type="text/css">
    <link rel="stylesheet" href="/vendors/slick/slick-theme.css" type="text/css">
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h6 class="card-title">بنر صفحه اصلی</h6>
            <div class="slick-multiple">
                @foreach(\App\Models\Banner::all() as $banner)
                    <div class="slick-slide-item">
                        <img src="{{ $banner->path }}" class="img-fluid" alt="image">
                    </div>
                @endforeach
            </div>
            <div class="row mt-5">
                <form action="{{ route('banners.upload') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="images">انتخاب تصاویر بنر</label>
                        <input type="file" name="images[]" class="form-control" multiple>
                        @error('images')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @error('images.*')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">آپلود</button>
                </form>
            </div>
            <h6 class="card-title mt-3 border-top border-1 pt-3">بنر میانی صفحه اصلی</h6>
            <div class="slick-multiple">
                @foreach(\App\Models\Banner_mid::all() as $banner)
                    <div class="slick-slide-item">
                        <img src="{{ $banner->path }}" class="img-fluid" alt="image">
                    </div>
                @endforeach
            </div>
            <div class="row mt-5">
                <form action="{{ route('banners_mid.upload_mid') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="images_mid">انتخاب تصاویر بنر میانی</label>
                        <input type="file" name="images_mid[]" class="form-control" multiple>
                        @error('images_mid')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @error('images_mid.*')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">آپلود</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="/vendors/slick/slick.min.js"></script>
    <script src="/assets/js/examples/slick.js"></script>
@endsection
